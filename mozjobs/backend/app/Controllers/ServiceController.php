<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class ServiceController {
  public function index(): array { return ['resource' => 'Service', 'items' => (new JsonStore())->all('services')]; }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['professional_id', 'title', 'price']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $data['approved'] = false;
    return ['resource' => 'Service', 'saved' => (new JsonStore())->create('services', $data)];
  }
}
