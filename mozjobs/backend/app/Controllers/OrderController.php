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
      'delivery_status' => 'pending',
    ];

    $saved = (new JsonStore())->create('orders', $payload);
    $this->logTimeline((int)$saved['id'], 'created', 'Pedido criado e aguardando processamento');

    return ['resource' => 'Order', 'saved' => $saved];
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

    if (!$updated) return ['error' => 'order not found'];
    $this->logTimeline((int)$updated['id'], 'status_changed', 'Estado atualizado para '.$updated['status']);
    return ['resource' => 'Order', 'saved' => $updated];
  }

  public function submitDelivery(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'professional_id', 'notes']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $orderId = (int)$data['order_id'];
    $professionalId = (int)$data['professional_id'];

    $store = new JsonStore();
    $order = $store->findBy('orders', 'id', $orderId);
    if (!$order) return ['error' => 'order not found'];
    if ((int)($order['professional_id'] ?? 0) !== $professionalId) return ['error' => 'forbidden'];
    if (($order['status'] ?? '') === 'cancelled') return ['error' => 'order cancelled'];

    $updated = $store->update('orders', $orderId, function ($o) use ($data) {
      $o['status'] = 'in_progress';
      $o['delivery_status'] = 'submitted';
      $o['delivery_notes'] = trim((string)$data['notes']);
      $o['delivery_submitted_at'] = date('c');
      $o['updated_at'] = date('c');
      return $o;
    });

    $this->logTimeline($orderId, 'delivery_submitted', 'Entrega submetida pelo profissional');
    $this->notify((int)($updated['client_id'] ?? 0), 'Entrega submetida', 'O profissional submeteu a entrega do pedido #'.$orderId);

    return ['resource' => 'Order', 'saved' => $updated];
  }

  public function reviewDelivery(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'client_id', 'decision']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $decision = (string)$data['decision'];
    if (!in_array($decision, ['accept', 'reject'], true)) return ['error' => 'invalid decision'];

    $orderId = (int)$data['order_id'];
    $clientId = (int)$data['client_id'];

    $store = new JsonStore();
    $order = $store->findBy('orders', 'id', $orderId);
    if (!$order) return ['error' => 'order not found'];
    if ((int)($order['client_id'] ?? 0) !== $clientId) return ['error' => 'forbidden'];

    $updated = $store->update('orders', $orderId, function ($o) use ($decision, $data) {
      if ($decision === 'accept') {
        $o['delivery_status'] = 'accepted';
        $o['status'] = 'completed';
      } else {
        $o['delivery_status'] = 'rejected';
        $o['status'] = 'in_progress';
      }
      $o['delivery_review_notes'] = trim((string)($data['notes'] ?? ''));
      $o['delivery_reviewed_at'] = date('c');
      $o['updated_at'] = date('c');
      return $o;
    });

    $this->logTimeline($orderId, 'delivery_reviewed', 'Entrega '.$decision.' pelo cliente');
    $this->notify((int)($updated['professional_id'] ?? 0), 'Revisão de entrega', 'A entrega do pedido #'.$orderId.' foi '.$decision.' pelo cliente');

    return ['resource' => 'Order', 'saved' => $updated];
  }

  public function timeline(array $query = []): array {
    $orderId = (int)($query['order_id'] ?? 0);
    if ($orderId <= 0) return ['error' => 'missing: order_id'];

    $items = (new JsonStore())->all('order_timeline');
    $items = array_values(array_filter($items, fn($i) => (int)($i['order_id'] ?? 0) === $orderId));
    usort($items, fn($a, $b) => strcmp((string)($a['created_at'] ?? ''), (string)($b['created_at'] ?? '')));

    return ['resource' => 'OrderTimeline', 'items' => $items];
  }

  private function logTimeline(int $orderId, string $event, string $message): void {
    (new JsonStore())->create('order_timeline', [
      'order_id' => $orderId,
      'event' => $event,
      'message' => $message,
    ]);
  }

  private function notify(int $userId, string $title, string $body): void {
    if ($userId <= 0) return;
    (new JsonStore())->create('notifications', [
      'user_id' => $userId,
      'title' => $title,
      'body' => $body,
      'read' => false,
      'priority' => 'high',
    ]);
  }
}
