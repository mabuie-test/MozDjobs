<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class ReviewController {
  public function index(): array { return ['resource' => 'Review', 'items' => (new JsonStore())->all('reviews')]; }
  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'reviewer_id', 'reviewed_id', 'rating']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];
    return ['resource' => 'Review', 'saved' => (new JsonStore())->create('reviews', $data)];
  }
}
