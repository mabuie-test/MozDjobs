<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class DisputeController {
  public function index(): array {
    return ['resource' => 'Dispute', 'items' => (new JsonStore())->all('disputes')];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'opened_by', 'reason']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $orderId = (int)$data['order_id'];
    $order = $store->findBy('orders', 'id', $orderId);
    if (!$order) return ['error' => 'order not found'];

    $dispute = $store->create('disputes', [
      'order_id' => $orderId,
      'opened_by' => (int)$data['opened_by'],
      'reason' => trim((string)$data['reason']),
      'status' => 'open',
    ]);

    $store->update('orders', $orderId, function ($o) {
      $o['status'] = 'disputed';
      $o['updated_at'] = date('c');
      return $o;
    });

    $this->notify((int)($order['client_id'] ?? 0), 'Disputa aberta', 'Foi aberta uma disputa no pedido #'.$orderId);
    $this->notify((int)($order['professional_id'] ?? 0), 'Disputa aberta', 'Foi aberta uma disputa no pedido #'.$orderId);

    return ['resource' => 'Dispute', 'saved' => $dispute];
  }

  public function resolve(array $data): array {
    $missing = Validator::requireFields($data, ['dispute_id', 'resolution']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $store = new JsonStore();
    $disputeId = (int)$data['dispute_id'];
    $existing = $store->findBy('disputes', 'id', $disputeId);
    if (!$existing) return ['error' => 'dispute not found'];

    $resolution = trim((string)$data['resolution']);
    $resolved = $store->update('disputes', $disputeId, function ($d) use ($resolution) {
      $d['status'] = 'resolved';
      $d['resolution'] = $resolution;
      $d['resolved_at'] = date('c');
      return $d;
    });

    $orderId = (int)($existing['order_id'] ?? 0);
    $order = $store->findBy('orders', 'id', $orderId);
    if ($order) {
      $nextStatus = str_contains(mb_strtolower($resolution), 'cancel') ? 'cancelled' : 'in_progress';
      $store->update('orders', $orderId, function ($o) use ($nextStatus) {
        $o['status'] = $nextStatus;
        $o['updated_at'] = date('c');
        return $o;
      });

      $this->notify((int)($order['client_id'] ?? 0), 'Disputa resolvida', 'A disputa do pedido #'.$orderId.' foi resolvida.');
      $this->notify((int)($order['professional_id'] ?? 0), 'Disputa resolvida', 'A disputa do pedido #'.$orderId.' foi resolvida.');
    }

    return ['resource' => 'Dispute', 'saved' => $resolved];
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
