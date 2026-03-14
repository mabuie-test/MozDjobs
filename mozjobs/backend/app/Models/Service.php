<?php
namespace App\Models;

class Service {
  public function __construct(
    public int $id = 0,
    public int $professional_id = 0,
    public string $title = '',
    public float $price = 0,
    public bool $approved = false
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
