<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class JobController {
  public function index(): array { return ['resource' => 'Job', 'items' => (new JsonStore())->all('jobs')]; }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['company_id', 'title', 'description']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $data['approved'] = false;
    return ['resource' => 'Job', 'saved' => (new JsonStore())->create('jobs', $data)];
  }
}
