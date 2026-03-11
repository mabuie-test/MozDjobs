<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class FeedController {
  public function index(array $query = []): array {
    $store = new JsonStore();
    $posts = $store->all('feed_posts');
    $comments = $store->all('feed_comments');
    $reactions = $store->all('feed_reactions');

    if (isset($query['user_id'])) {
      $uid = (int)$query['user_id'];
      $posts = array_values(array_filter($posts, fn($p) => (int)($p['author_id'] ?? 0) === $uid));
    }

    usort($posts, fn($a, $b) => strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? '')));

    $items = array_map(function ($post) use ($comments, $reactions) {
      $pid = (int)($post['id'] ?? 0);
      $postComments = array_values(array_filter($comments, fn($c) => (int)($c['post_id'] ?? 0) === $pid));
      $postReactions = array_values(array_filter($reactions, fn($r) => (int)($r['post_id'] ?? 0) === $pid));
      $post['comments_count'] = count($postComments);
      $post['reactions_count'] = count($postReactions);
      $post['top_reaction'] = $postReactions ? ($postReactions[0]['type'] ?? 'like') : 'none';
      $post['comments'] = array_slice($postComments, -3);
      return $post;
    }, $posts);

    return ['resource' => 'FeedPost', 'items' => $items];
  }

  public function createPost(array $data): array {
    $missing = Validator::requireFields($data, ['author_id', 'author_name', 'content']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('feed_posts', [
      'author_id' => (int)$data['author_id'],
      'author_name' => (string)$data['author_name'],
      'content' => trim((string)$data['content']),
      'media_url' => (string)($data['media_url'] ?? ''),
      'post_type' => (string)($data['post_type'] ?? 'status'),
    ]);

    return ['resource' => 'FeedPost', 'saved' => $saved];
  }

  public function react(array $data): array {
    $missing = Validator::requireFields($data, ['post_id', 'user_id', 'type']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('feed_reactions', [
      'post_id' => (int)$data['post_id'],
      'user_id' => (int)$data['user_id'],
      'type' => (string)$data['type'],
    ]);

    return ['resource' => 'FeedReaction', 'saved' => $saved];
  }

  public function comment(array $data): array {
    $missing = Validator::requireFields($data, ['post_id', 'user_id', 'comment']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('feed_comments', [
      'post_id' => (int)$data['post_id'],
      'user_id' => (int)$data['user_id'],
      'comment' => trim((string)$data['comment']),
    ]);

    return ['resource' => 'FeedComment', 'saved' => $saved];
  }
}
