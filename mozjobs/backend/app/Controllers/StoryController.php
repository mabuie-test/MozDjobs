<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class StoryController {
  public function index(): array {
    $stories = (new JsonStore())->all('stories');
    usort($stories, fn($a, $b) => strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? '')));
    return ['resource' => 'Story', 'items' => $stories];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['user_id', 'user_name', 'text']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('stories', [
      'user_id' => (int)$data['user_id'],
      'user_name' => (string)$data['user_name'],
      'text' => (string)$data['text'],
      'bg' => (string)($data['bg'] ?? '#1d4ed8'),
    ]);

    return ['resource' => 'Story', 'saved' => $saved];
  }
}
