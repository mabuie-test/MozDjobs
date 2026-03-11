<?php
namespace App\Services;
class MpesaService {
  public function charge(float $amount): array {
    return ['provider' => 'mpesa', 'amount' => $amount, 'status' => $amount > 0 ? 'paid' : 'failed'];
  }
}
