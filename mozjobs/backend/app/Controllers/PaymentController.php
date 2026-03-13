<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;
use App\Services\EmolaService;
use App\Services\MkeshService;
use App\Services\MpesaService;

class PaymentController {
  public function index(array $query = []): array {
    $items = (new JsonStore())->all('payments');
    if (isset($query['order_id'])) {
      $oid = (int)$query['order_id'];
      $items = array_values(array_filter($items, fn($i) => (int)($i['order_id'] ?? 0) === $oid));
    }
    return ['resource' => 'Payment', 'items' => $items];
  }

  public function createEscrow(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'provider', 'amount']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $amount = (float)$data['amount'];
    if ($amount <= 0) return ['error' => 'invalid amount'];

    $store = new JsonStore();
    $orderId = (int)$data['order_id'];
    $order = $store->findBy('orders', 'id', $orderId);
    if (!$order) return ['error' => 'order not found'];

    $provider = strtolower((string)$data['provider']);
    $result = match ($provider) {
      'mpesa' => (new MpesaService())->charge($amount),
      'emola' => (new EmolaService())->charge($amount),
      'mkesh' => (new MkeshService())->charge($amount),
      default => ['status' => 'failed', 'error' => 'unsupported provider']
    };

    if (($result['status'] ?? 'failed') === 'failed') return ['error' => $result['error'] ?? 'payment failed'];

    $existingHeld = array_values(array_filter($store->all('payments'), fn($p) => (int)($p['order_id'] ?? 0) === $orderId && (string)($p['escrow_status'] ?? '') === 'held'));
    if ($existingHeld) return ['error' => 'escrow already held for order'];

    $payment = $store->create('payments', [
      'order_id' => $orderId,
      'provider' => $provider,
      'amount' => $amount,
      'status' => 'paid',
      'escrow_status' => 'held',
      'transaction_ref' => strtoupper($provider).'-'.uniqid(),
    ]);

    $store->update('orders', $orderId, function ($o) {
      $o['escrow_status'] = 'held';
      $o['updated_at'] = date('c');
      return $o;
    });

    return ['resource' => 'Payment', 'saved' => $payment];
  }

  public function releaseEscrow(array $data): array {
    $missing = Validator::requireFields($data, ['payment_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $paymentId = (int)$data['payment_id'];
    $store = new JsonStore();
    $payment = $store->findBy('payments', 'id', $paymentId);
    if (!$payment) return ['error' => 'payment not found'];

    if ((string)($payment['escrow_status'] ?? '') === 'released') return ['error' => 'escrow already released'];

    $order = $store->findBy('orders', 'id', (int)($payment['order_id'] ?? 0));
    if (!$order) return ['error' => 'order not found'];
    if ((string)($order['status'] ?? '') !== 'completed') {
      return ['error' => 'order must be completed before escrow release'];
    }

    $updated = $store->update('payments', $paymentId, function ($p) {
      $p['escrow_status'] = 'released';
      $p['released_at'] = date('c');
      return $p;
    });

    $store->update('orders', (int)$order['id'], function ($o) {
      $o['escrow_status'] = 'released';
      $o['updated_at'] = date('c');
      return $o;
    });

    $this->notify((int)($order['professional_id'] ?? 0), 'Escrow libertado', 'O pagamento do pedido #'.(int)$order['id'].' foi libertado.');

    return ['resource' => 'Payment', 'saved' => $updated];
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
