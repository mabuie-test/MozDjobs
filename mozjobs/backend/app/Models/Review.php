<?php
namespace App\Models;

class Review {
  public function __construct(
    public int $id = 0,
    public int $reviewer_id = 0,
    public int $reviewed_id = 0,
    public int $rating = 5,
    public string $comment = ''
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
