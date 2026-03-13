<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;
use App\Services\EmolaService;
use App\Services\MkeshService;
use App\Services\MpesaService;

class PaymentController {
  public function index(): array {
    return ['resource' => 'Payment', 'items' => (new JsonStore())->all('payments')];
  }

  public function createEscrow(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'provider', 'amount']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $provider = strtolower((string) $data['provider']);
    $result = match ($provider) {
      'mpesa' => (new MpesaService())->charge((float) $data['amount']),
      'emola' => (new EmolaService())->charge((float) $data['amount']),
      'mkesh' => (new MkeshService())->charge((float) $data['amount']),
      default => ['status' => 'failed', 'error' => 'unsupported provider']
    };

    if (($result['status'] ?? 'failed') === 'failed') {
      return ['error' => $result['error'] ?? 'payment failed'];
    }

    $payment = (new JsonStore())->create('payments', [
      'order_id' => (int) $data['order_id'],
      'provider' => $provider,
      'amount' => (float) $data['amount'],
      'status' => 'paid',
      'escrow_status' => 'held',
      'transaction_ref' => strtoupper($provider).'-'.uniqid()
    ]);

    return ['resource' => 'Payment', 'saved' => $payment];
  }

  public function releaseEscrow(array $data): array {
    $missing = Validator::requireFields($data, ['payment_id']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $updated = (new JsonStore())->update('payments', (int) $data['payment_id'], function ($payment) {
      $payment['escrow_status'] = 'released';
      $payment['released_at'] = date('c');
      return $payment;
    });

    return $updated ? ['resource' => 'Payment', 'saved' => $updated] : ['error' => 'payment not found'];
  }
}
