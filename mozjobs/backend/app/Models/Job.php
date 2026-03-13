<?php
namespace App\Models;

class Job {
  public function __construct(
    public int $id = 0,
    public int $company_id = 0,
    public string $title = '',
    public string $description = '',
    public string $status = 'open'
  ) {}

  public function toArray(): array { return get_object_vars($this); }
}
