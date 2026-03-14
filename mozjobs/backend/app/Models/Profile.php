<?php
namespace App\Models;

class Profile {
  public function __construct(
    public int $id = 0,
    public int $user_id = 0,
    public string $location = '',
    public string $skills = '',
    public string $portfolio_url = ''
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
