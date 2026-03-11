<?php
namespace App\Controllers;
class ServiceController {
  public function index(): array { return ['resource' => 'Service', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Service', 'saved' => $data]; }
}
