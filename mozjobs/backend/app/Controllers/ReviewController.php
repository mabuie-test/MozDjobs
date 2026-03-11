<?php
namespace App\Controllers;
class ReviewController {
  public function index(): array { return ['resource' => 'Review', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Review', 'saved' => $data]; }
}
