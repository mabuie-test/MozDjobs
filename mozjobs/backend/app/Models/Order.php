<?php
namespace App\Models;

class Order {
  public function __construct(
    public int $id = 0,
    public int $client_id = 0,
    public int $professional_id = 0,
    public float $amount = 0,
    public string $status = 'open'
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
