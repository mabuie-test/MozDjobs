<?php
namespace App\Models;

class Payment {
  public function __construct(
    public int $id = 0,
    public int $order_id = 0,
    public string $provider = 'mpesa',
    public float $amount = 0,
    public string $escrow_status = 'held'
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
