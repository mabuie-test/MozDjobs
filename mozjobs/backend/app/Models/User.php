<?php
namespace App\Models;

class User {
  public function __construct(
    public int $id = 0,
    public string $name = '',
    public string $email = '',
    public string $role = 'professional',
    public string $status = 'active'
  ) {}

  public function toArray(): array {
    return get_object_vars($this);
  }
}
