<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class OrderController {
  public function index(array $query = []): array {
    $items = (new JsonStore())->all('orders');

    if (isset($query['client_id'])) {
      $cid = (int)$query['client_id'];
      $items = array_values(array_filter($items, fn($o) => (int)($o['client_id'] ?? 0) === $cid));
    }

    if (isset($query['professional_id'])) {
      $pid = (int)$query['professional_id'];
      $items = array_values(array_filter($items, fn($o) => (int)($o['professional_id'] ?? 0) === $pid));
    }

    if (isset($query['status'])) {
      $status = (string)$query['status'];
      $items = array_values(array_filter($items, fn($o) => (string)($o['status'] ?? '') === $status));
    }

    usort($items, fn($a, $b) => strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? '')));
    return ['resource' => 'Order', 'items' => $items];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['client_id', 'professional_id', 'amount']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $amount = (float)$data['amount'];
    if ($amount <= 0) return ['error' => 'invalid amount'];

    $payload = [
      'client_id' => (int)$data['client_id'],
      'professional_id' => (int)$data['professional_id'],
      'amount' => $amount,
      'currency' => (string)($data['currency'] ?? 'MZN'),
      'status' => (string)($data['status'] ?? 'open'),
      'escrow_status' => (string)($data['escrow_status'] ?? 'pending'),
      'description' => (string)($data['description'] ?? ''),
    ];

    return ['resource' => 'Order', 'saved' => (new JsonStore())->create('orders', $payload)];
  }

  public function updateStatus(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'status']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $allowed = ['open', 'in_progress', 'completed', 'cancelled', 'disputed'];
    if (!in_array($data['status'], $allowed, true)) return ['error' => 'invalid status'];

    $updated = (new JsonStore())->update('orders', (int)$data['order_id'], function ($order) use ($data) {
      $order['status'] = $data['status'];
      $order['updated_at'] = date('c');
      return $order;
    });

    return $updated ? ['resource' => 'Order', 'saved' => $updated] : ['error' => 'order not found'];
  }
}
