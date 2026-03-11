<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class OrderController {
  public function index(): array { return ['resource' => 'Order', 'items' => (new JsonStore())->all('orders')]; }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['client_id', 'professional_id', 'amount']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $data['status'] = $data['status'] ?? 'open';
    $data['escrow_status'] = $data['escrow_status'] ?? 'held';
    return ['resource' => 'Order', 'saved' => (new JsonStore())->create('orders', $data)];
  }

  public function updateStatus(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'status']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $allowed = ['open', 'in_progress', 'completed', 'cancelled', 'disputed'];
    if (!in_array($data['status'], $allowed, true)) {
      return ['error' => 'invalid status'];
    }

    $updated = (new JsonStore())->update('orders', (int) $data['order_id'], function ($order) use ($data) {
      $order['status'] = $data['status'];
      return $order;
    });

    return $updated ? ['resource' => 'Order', 'saved' => $updated] : ['error' => 'order not found'];
  }
}
