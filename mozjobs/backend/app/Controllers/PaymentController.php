<?php
namespace App\Controllers;
class PaymentController {
  public function index(): array { return ['resource' => 'Payment', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Payment', 'saved' => $data]; }
}
