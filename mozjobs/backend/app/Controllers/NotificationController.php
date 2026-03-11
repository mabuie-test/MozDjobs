<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class NotificationController {
  public function index(array $query = []): array {
    $items = (new JsonStore())->all('notifications');
    if (isset($query['user_id'])) {
      $uid = (int)$query['user_id'];
      $items = array_values(array_filter($items, fn($i) => (int)($i['user_id'] ?? 0) === $uid));
    }
    return ['resource' => 'Notification', 'items' => $items];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['user_id', 'title', 'body']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('notifications', [
      'user_id' => (int)$data['user_id'],
      'title' => (string)$data['title'],
      'body' => (string)$data['body'],
      'read' => false,
    ]);

    return ['resource' => 'Notification', 'saved' => $saved];
  }

  public function markRead(array $data): array {
    $missing = Validator::requireFields($data, ['notification_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $updated = (new JsonStore())->update('notifications', (int)$data['notification_id'], function ($item) {
      $item['read'] = true;
      return $item;
    });
    return $updated ? ['resource' => 'Notification', 'saved' => $updated] : ['error' => 'notification not found'];
  }
}
