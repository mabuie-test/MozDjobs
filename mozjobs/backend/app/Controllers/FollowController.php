<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class FollowController {
  public function index(array $query = []): array {
    $items = (new JsonStore())->all('follows');
    if (isset($query['follower_id'])) {
      $fid = (int)$query['follower_id'];
      $items = array_values(array_filter($items, fn($f) => (int)($f['follower_id'] ?? 0) === $fid));
    }
    return ['resource' => 'Follow', 'items' => $items];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['follower_id', 'followed_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $followerId = (int)$data['follower_id'];
    $followedId = (int)$data['followed_id'];
    if ($followerId === $followedId) return ['error' => 'cannot follow self'];

    $store = new JsonStore();
    $items = $store->all('follows');
    foreach ($items as $item) {
      if ((int)($item['follower_id'] ?? 0) === $followerId && (int)($item['followed_id'] ?? 0) === $followedId) {
        return ['resource' => 'Follow', 'saved' => $item, 'deduped' => true];
      }
    }

    $saved = $store->create('follows', [
      'follower_id' => $followerId,
      'followed_id' => $followedId,
    ]);

    return ['resource' => 'Follow', 'saved' => $saved, 'deduped' => false];
  }

  public function unfollow(array $data): array {
    $missing = Validator::requireFields($data, ['follower_id', 'followed_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $followerId = (int)$data['follower_id'];
    $followedId = (int)$data['followed_id'];
    $store = new JsonStore();
    $items = $store->all('follows');
    $filtered = array_values(array_filter($items, fn($f) => !((int)($f['follower_id'] ?? 0) === $followerId && (int)($f['followed_id'] ?? 0) === $followedId)));

    if (count($filtered) === count($items)) return ['error' => 'follow not found'];

    $store->saveAll('follows', $filtered);
    return ['resource' => 'Follow', 'removed' => true, 'follower_id' => $followerId, 'followed_id' => $followedId];
  }

  public function suggestions(array $query = []): array {
    $followerId = (int)($query['follower_id'] ?? 0);
    if ($followerId <= 0) return ['error' => 'missing: follower_id'];

    $store = new JsonStore();
    $users = $store->all('users');
    $follows = $store->all('follows');
    $followed = array_map(fn($f) => (int)($f['followed_id'] ?? 0), array_values(array_filter($follows, fn($f) => (int)($f['follower_id'] ?? 0) === $followerId)));

    $suggestions = array_values(array_filter($users, function ($user) use ($followerId, $followed) {
      $uid = (int)($user['id'] ?? 0);
      return $uid > 0 && $uid !== $followerId && !in_array($uid, $followed, true);
    }));

    return ['resource' => 'FollowSuggestion', 'items' => array_slice($suggestions, 0, 8)];
  }
}
