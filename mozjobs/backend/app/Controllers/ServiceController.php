<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class ServiceController {
  public function index(array $query = []): array {
    $services = (new JsonStore())->all('services');

    if (!empty($query['q'])) {
      $q = mb_strtolower((string) $query['q']);
      $services = array_values(array_filter($services, fn($s) =>
        str_contains(mb_strtolower((string) ($s['title'] ?? '')), $q)
        || str_contains(mb_strtolower((string) ($s['description'] ?? '')), $q)
      ));
    }

    return ['resource' => 'Service', 'items' => $services];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['professional_id', 'title', 'price']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $data['approved'] = false;
    return ['resource' => 'Service', 'saved' => (new JsonStore())->create('services', $data)];
  }
}
