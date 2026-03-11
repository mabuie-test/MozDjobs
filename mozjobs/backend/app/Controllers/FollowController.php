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

    $saved = (new JsonStore())->create('follows', [
      'follower_id' => (int)$data['follower_id'],
      'followed_id' => (int)$data['followed_id']
    ]);

    return ['resource' => 'Follow', 'saved' => $saved];
  }
}
