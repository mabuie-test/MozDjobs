<?php
namespace App\Controllers;
class ProfileController {
  public function index(): array { return ['resource' => 'Profile', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Profile', 'saved' => $data]; }
}
