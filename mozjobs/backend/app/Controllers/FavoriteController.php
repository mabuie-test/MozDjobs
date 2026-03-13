<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class FavoriteController {
  public function index(array $query = []): array {
    $items = (new JsonStore())->all('favorites');
    if (isset($query['user_id'])) {
      $uid = (int)$query['user_id'];
      $items = array_values(array_filter($items, fn($i) => (int)($i['user_id'] ?? 0) === $uid));
    }
    return ['resource' => 'Favorite', 'items' => $items];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['user_id', 'entity_type', 'entity_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    return ['resource' => 'Favorite', 'saved' => (new JsonStore())->create('favorites', [
      'user_id' => (int)$data['user_id'],
      'entity_type' => (string)$data['entity_type'],
      'entity_id' => (int)$data['entity_id']
    ])];
  }
}
