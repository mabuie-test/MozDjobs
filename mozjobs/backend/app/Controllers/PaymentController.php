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

    $provider = strtolower((string)$data['provider']);
    $result = match ($provider) {
      'mpesa' => (new MpesaService())->charge($amount),
      'emola' => (new EmolaService())->charge($amount),
      'mkesh' => (new MkeshService())->charge($amount),
      default => ['status' => 'failed', 'error' => 'unsupported provider']
    };

    if (($result['status'] ?? 'failed') === 'failed') return ['error' => $result['error'] ?? 'payment failed'];

    $orderId = (int)$data['order_id'];
    $store = new JsonStore();
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

    return ['resource' => 'Payment', 'saved' => $payment];
  }

  public function releaseEscrow(array $data): array {
    $missing = Validator::requireFields($data, ['payment_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $paymentId = (int)$data['payment_id'];
    $store = new JsonStore();
    $payment = $store->findBy('payments', 'id', $paymentId);
    if (!$payment) return ['error' => 'payment not found'];

    if ((string)($payment['escrow_status'] ?? '') === 'released') {
      return ['error' => 'escrow already released'];
    }

    $updated = $store->update('payments', $paymentId, function ($p) {
      $p['escrow_status'] = 'released';
      $p['released_at'] = date('c');
      return $p;
    });

    return ['resource' => 'Payment', 'saved' => $updated];
  }
}
