<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class ChatController {
  public function index(array $query = []): array {
    $messages = (new JsonStore())->all('chats');
    if (isset($query['order_id'])) {
      $messages = array_values(array_filter($messages, fn($m) => (int)($m['order_id'] ?? 0) === (int)$query['order_id']));
    }
    return ['resource' => 'Chat', 'items' => $messages];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'sender_id', 'message']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];
    return ['resource' => 'Chat', 'saved' => (new JsonStore())->create('chats', $data)];
  }
}
