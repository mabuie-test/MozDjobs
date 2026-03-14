<?php
require __DIR__.'/../app/Controllers/ReviewController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$c = new App\Controllers\ReviewController();
$c->store(['order_id' => 10, 'reviewer_id' => 1, 'reviewed_id' => 55, 'rating' => 4]);
$c->store(['order_id' => 11, 'reviewer_id' => 2, 'reviewed_id' => 55, 'rating' => 5]);
$s = $c->summary(['reviewed_id' => 55]);
if (($s['average_rating'] ?? 0) < 4.4 || ($s['reviews_count'] ?? 0) !== 2) {
  echo "review summary test failed\n";
  exit(1);
}

echo "review summary test ok\n";
