<?php
namespace App\Controllers;

use App\Helpers\JsonStore;

class UserController {
  public function index(): array {
    $users = (new JsonStore())->all('users');
    return ['resource' => 'User', 'items' => array_map(function ($u) {
      unset($u['password_hash']);
      return $u;
    }, $users)];
  }

  public function store(array $data): array {
    return ['resource' => 'User', 'saved' => (new JsonStore())->create('users', $data)];
  }
}
