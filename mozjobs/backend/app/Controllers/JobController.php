<?php
namespace App\Controllers;
class JobController {
  public function index(): array { return ['resource' => 'Job', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Job', 'saved' => $data]; }
}
