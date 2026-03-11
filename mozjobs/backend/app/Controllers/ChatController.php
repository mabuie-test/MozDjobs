<?php
namespace App\Controllers;
class ChatController {
  public function index(): array { return ['resource' => 'Chat', 'items' => []]; }
  public function store(array $data): array { return ['resource' => 'Chat', 'saved' => $data]; }
}
