<?php
namespace App\Controllers;

use App\Helpers\JsonStore;

class ProfileController {
  public function index(): array { return ['resource' => 'Profile', 'items' => (new JsonStore())->all('profiles')]; }
  public function store(array $data): array { return ['resource' => 'Profile', 'saved' => (new JsonStore())->create('profiles', $data)]; }
}
