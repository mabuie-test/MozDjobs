<?php
require __DIR__.'/../app/Controllers/FeedController.php';
require __DIR__.'/../app/Controllers/FollowController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$store = new App\Helpers\JsonStore();
$store->saveAll('feed_posts', []);
$store->saveAll('follows', []);
$store->saveAll('users', [
  ['id' => 1, 'name' => 'Ana'],
  ['id' => 2, 'name' => 'Paulo'],
  ['id' => 3, 'name' => 'Lina'],
]);

$feed = new App\Controllers\FeedController();
$feed->createPost(['author_id' => 1, 'author_name' => 'Ana', 'content' => 'Nova vaga #PHP #Remote']);
$feed->createPost(['author_id' => 2, 'author_name' => 'Paulo', 'content' => 'Serviço de design #Design #Remote']);
$trend = $feed->trending(['limit' => 2]);
if (($trend['items'][0]['tag'] ?? '') !== '#remote') {
  echo "trending tags failed\n";
  exit(1);
}

$follow = new App\Controllers\FollowController();
$first = $follow->store(['follower_id' => 1, 'followed_id' => 2]);
$dupe = $follow->store(['follower_id' => 1, 'followed_id' => 2]);
if (($first['deduped'] ?? true) !== false || ($dupe['deduped'] ?? false) !== true) {
  echo "follow dedupe failed\n";
  exit(1);
}

$suggestions = $follow->suggestions(['follower_id' => 1]);
if (count($suggestions['items'] ?? []) === 0) {
  echo "follow suggestions failed\n";
  exit(1);
}

$removed = $follow->unfollow(['follower_id' => 1, 'followed_id' => 2]);
if (($removed['removed'] ?? false) !== true) {
  echo "unfollow failed\n";
  exit(1);
}

echo "social enhanced test ok\n";
