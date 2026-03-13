<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class FeedController {
  private array $allowedPostTypes = ['status', 'job', 'service', 'update'];
  private array $allowedReactions = ['like', 'love', 'care', 'celebrate', 'insightful'];

  public function index(array $query = []): array {
    $store = new JsonStore();
    $posts = $store->all('feed_posts');
    $comments = $store->all('feed_comments');
    $reactions = $store->all('feed_reactions');

    if (isset($query['user_id'])) {
      $uid = (int)$query['user_id'];
      $posts = array_values(array_filter($posts, fn($p) => (int)($p['author_id'] ?? 0) === $uid));
    }

    $sort = (string)($query['sort'] ?? 'recent');
    if ($sort === 'popular') {
      usort($posts, function ($a, $b) use ($comments, $reactions) {
        $scoreA = $this->engagementScore((int)($a['id'] ?? 0), $comments, $reactions);
        $scoreB = $this->engagementScore((int)($b['id'] ?? 0), $comments, $reactions);
        return $scoreB <=> $scoreA;
      });
    } else {
      usort($posts, fn($a, $b) => strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? '')));
    }

    $offset = max(0, (int)($query['offset'] ?? 0));
    $limit = max(1, min(50, (int)($query['limit'] ?? 20)));
    $slice = array_slice($posts, $offset, $limit);

    $items = array_map(function ($post) use ($comments, $reactions) {
      $pid = (int)($post['id'] ?? 0);
      $postComments = array_values(array_filter($comments, fn($c) => (int)($c['post_id'] ?? 0) === $pid));
      $postReactions = array_values(array_filter($reactions, fn($r) => (int)($r['post_id'] ?? 0) === $pid));
      $post['comments_count'] = count($postComments);
      $post['reactions_count'] = count($postReactions);
      $post['top_reaction'] = $postReactions ? ($postReactions[0]['type'] ?? 'like') : 'none';
      $post['comments'] = array_slice($postComments, -3);
      return $post;
    }, $slice);

    return [
      'resource' => 'FeedPost',
      'items' => $items,
      'meta' => [
        'offset' => $offset,
        'limit' => $limit,
        'returned' => count($items),
        'total' => count($posts),
        'has_more' => ($offset + count($items)) < count($posts),
      ],
    ];
  }

  public function createPost(array $data): array {
    $missing = Validator::requireFields($data, ['author_id', 'author_name', 'content']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $postType = (string)($data['post_type'] ?? 'status');
    if (!in_array($postType, $this->allowedPostTypes, true)) {
      return ['error' => 'invalid post_type'];
    }

    $saved = (new JsonStore())->create('feed_posts', [
      'author_id' => (int)$data['author_id'],
      'author_name' => (string)$data['author_name'],
      'content' => trim((string)$data['content']),
      'media_url' => (string)($data['media_url'] ?? ''),
      'post_type' => $postType,
      'updated_at' => date('c'),
    ]);

    return ['resource' => 'FeedPost', 'saved' => $saved];
  }

  public function updatePost(array $data): array {
    $missing = Validator::requireFields($data, ['id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $id = (int)$data['id'];
    $content = trim((string)($data['content'] ?? ''));
    $postType = (string)($data['post_type'] ?? 'status');
    if ($content === '') return ['error' => 'content required'];
    if (!in_array($postType, $this->allowedPostTypes, true)) return ['error' => 'invalid post_type'];

    $updated = (new JsonStore())->update('feed_posts', $id, function ($item) use ($content, $postType, $data) {
      $item['content'] = $content;
      $item['post_type'] = $postType;
      $item['media_url'] = (string)($data['media_url'] ?? ($item['media_url'] ?? ''));
      $item['updated_at'] = date('c');
      return $item;
    });

    if (!$updated) return ['error' => 'post not found'];
    return ['resource' => 'FeedPost', 'saved' => $updated];
  }

  public function deletePost(array $data): array {
    $missing = Validator::requireFields($data, ['id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $id = (int)$data['id'];

    $posts = $store->all('feed_posts');
    $before = count($posts);
    $posts = array_values(array_filter($posts, fn($p) => (int)($p['id'] ?? 0) !== $id));
    if ($before === count($posts)) return ['error' => 'post not found'];

    $store->saveAll('feed_posts', $posts);
    $store->saveAll('feed_comments', array_values(array_filter($store->all('feed_comments'), fn($c) => (int)($c['post_id'] ?? 0) !== $id)));
    $store->saveAll('feed_reactions', array_values(array_filter($store->all('feed_reactions'), fn($r) => (int)($r['post_id'] ?? 0) !== $id)));

    return ['resource' => 'FeedPost', 'deleted_id' => $id];
  }

  public function react(array $data): array {
    $missing = Validator::requireFields($data, ['post_id', 'user_id', 'type']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $type = (string)$data['type'];
    if (!in_array($type, $this->allowedReactions, true)) {
      return ['error' => 'invalid reaction type'];
    }

    $store = new JsonStore();
    $postId = (int)$data['post_id'];
    $userId = (int)$data['user_id'];
    $reactions = $store->all('feed_reactions');

    foreach ($reactions as $index => $reaction) {
      if ((int)($reaction['post_id'] ?? 0) === $postId && (int)($reaction['user_id'] ?? 0) === $userId) {
        $reactions[$index]['type'] = $type;
        $reactions[$index]['updated_at'] = date('c');
        $store->saveAll('feed_reactions', $reactions);
        return ['resource' => 'FeedReaction', 'saved' => $reactions[$index], 'updated' => true];
      }
    }

    $saved = $store->create('feed_reactions', [
      'post_id' => $postId,
      'user_id' => $userId,
      'type' => $type,
      'updated_at' => date('c'),
    ]);

    return ['resource' => 'FeedReaction', 'saved' => $saved, 'updated' => false];
  }

  public function removeReaction(array $data): array {
    $missing = Validator::requireFields($data, ['post_id', 'user_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $postId = (int)$data['post_id'];
    $userId = (int)$data['user_id'];
    $reactions = $store->all('feed_reactions');
    $filtered = array_values(array_filter($reactions, fn($r) => !((int)($r['post_id'] ?? 0) === $postId && (int)($r['user_id'] ?? 0) === $userId)));

    if (count($filtered) === count($reactions)) {
      return ['error' => 'reaction not found'];
    }

    $store->saveAll('feed_reactions', $filtered);
    return ['resource' => 'FeedReaction', 'removed' => true, 'post_id' => $postId, 'user_id' => $userId];
  }

  public function comment(array $data): array {
    $missing = Validator::requireFields($data, ['post_id', 'user_id', 'comment']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('feed_comments', [
      'post_id' => (int)$data['post_id'],
      'user_id' => (int)$data['user_id'],
      'comment' => trim((string)$data['comment']),
      'updated_at' => date('c'),
    ]);

    return ['resource' => 'FeedComment', 'saved' => $saved];
  }

  public function updateComment(array $data): array {
    $missing = Validator::requireFields($data, ['id', 'comment']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $updated = (new JsonStore())->update('feed_comments', (int)$data['id'], function ($item) use ($data) {
      $item['comment'] = trim((string)$data['comment']);
      $item['updated_at'] = date('c');
      return $item;
    });

    if (!$updated) return ['error' => 'comment not found'];
    return ['resource' => 'FeedComment', 'saved' => $updated];
  }

  public function deleteComment(array $data): array {
    $missing = Validator::requireFields($data, ['id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $id = (int)$data['id'];
    $comments = $store->all('feed_comments');
    $filtered = array_values(array_filter($comments, fn($c) => (int)($c['id'] ?? 0) !== $id));

    if (count($filtered) === count($comments)) return ['error' => 'comment not found'];

    $store->saveAll('feed_comments', $filtered);
    return ['resource' => 'FeedComment', 'deleted_id' => $id];
  }


  public function trending(array $query = []): array {
    $limit = max(1, min(20, (int)($query['limit'] ?? 8)));
    $posts = (new JsonStore())->all('feed_posts');

    $tags = [];
    foreach ($posts as $post) {
      $content = (string)($post['content'] ?? '');
      if (preg_match_all('/#([\p{L}\p{N}_-]+)/u', $content, $matches)) {
        foreach ($matches[1] as $match) {
          $key = mb_strtolower($match);
          $tags[$key] = ($tags[$key] ?? 0) + 1;
        }
      }
    }

    arsort($tags);
    $items = [];
    foreach (array_slice($tags, 0, $limit, true) as $tag => $count) {
      $items[] = ['tag' => '#'.$tag, 'mentions' => $count];
    }

    return ['resource' => 'FeedTrend', 'items' => $items];
  }

  private function engagementScore(int $postId, array $comments, array $reactions): int {
    $commentsCount = count(array_filter($comments, fn($c) => (int)($c['post_id'] ?? 0) === $postId));
    $reactionsCount = count(array_filter($reactions, fn($r) => (int)($r['post_id'] ?? 0) === $postId));
    return ($commentsCount * 2) + $reactionsCount;
  }
}
