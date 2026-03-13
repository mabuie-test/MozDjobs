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

    if (($query['unread_only'] ?? '0') === '1') {
      $items = array_values(array_filter($items, fn($i) => ($i['read'] ?? false) !== true));
    }

    usort($items, fn($a, $b) => strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? '')));
    return ['resource' => 'Notification', 'items' => $items];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['user_id', 'title', 'body']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $saved = (new JsonStore())->create('notifications', [
      'user_id' => (int)$data['user_id'],
      'title' => trim((string)$data['title']),
      'body' => trim((string)$data['body']),
      'read' => false,
      'priority' => (string)($data['priority'] ?? 'normal'),
    ]);

    return ['resource' => 'Notification', 'saved' => $saved];
  }

  public function markRead(array $data): array {
    $missing = Validator::requireFields($data, ['notification_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $notification = $store->findBy('notifications', 'id', (int)$data['notification_id']);
    if (!$notification) return ['error' => 'notification not found'];

    if (!$this->canAccessNotification($data, $notification)) {
      return ['error' => 'forbidden'];
    }

    $updated = $store->update('notifications', (int)$data['notification_id'], function ($item) {
      $item['read'] = true;
      $item['read_at'] = date('c');
      return $item;
    });

    return ['resource' => 'Notification', 'saved' => $updated];
  }

  private function canAccessNotification(array $data, array $notification): bool {
    $authUser = $data['auth_user'] ?? null;
    if (!$authUser) return true;

    $authId = (int)($authUser['id'] ?? 0);
    $role = (string)($authUser['role'] ?? '');
    return $role === 'admin' || $authId === (int)($notification['user_id'] ?? 0);
  }
}
