<?php
namespace App\Controllers;
class UserController {
  public function index(): array { return ['resource' => 'User', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'User', 'saved' => $data]; }
}
