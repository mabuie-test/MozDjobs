<?php
namespace App\Controllers;
class OrderController {
  public function index(): array { return ['resource' => 'Order', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Order', 'saved' => $data]; }
}
