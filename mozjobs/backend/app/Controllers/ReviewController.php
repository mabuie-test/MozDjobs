<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class ReviewController {
  public function index(array $query = []): array {
    $reviews = (new JsonStore())->all('reviews');

    if (isset($query['reviewed_id'])) {
      $reviewedId = (int) $query['reviewed_id'];
      $reviews = array_values(array_filter($reviews, fn($r) => (int) ($r['reviewed_id'] ?? 0) === $reviewedId));
    }

    return ['resource' => 'Review', 'items' => $reviews];
  }

  public function summary(array $query = []): array {
    $target = (int) ($query['reviewed_id'] ?? 0);
    if ($target <= 0) {
      return ['error' => 'missing: reviewed_id'];
    }

    $reviews = array_values(array_filter((new JsonStore())->all('reviews'), fn($r) => (int)($r['reviewed_id'] ?? 0) === $target));
    $count = count($reviews);
    $avg = $count > 0 ? array_sum(array_map(fn($r) => (int)($r['rating'] ?? 0), $reviews)) / $count : 0;

    return ['reviewed_id' => $target, 'reviews_count' => $count, 'average_rating' => round($avg, 2)];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'reviewer_id', 'reviewed_id', 'rating']);
    if ($missing) return ['error' => 'missing: '.implode(',', $missing)];

    $rating = (int) $data['rating'];
    if ($rating < 1 || $rating > 5) {
      return ['error' => 'rating must be between 1 and 5'];
    }

    return ['resource' => 'Review', 'saved' => (new JsonStore())->create('reviews', $data)];
  }
}
