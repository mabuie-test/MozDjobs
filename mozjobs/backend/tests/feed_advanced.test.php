<?php
require __DIR__.'/../app/Controllers/FeedController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$store = new App\Helpers\JsonStore();
$store->saveAll('feed_posts', []);
$store->saveAll('feed_comments', []);
$store->saveAll('feed_reactions', []);

$feed = new App\Controllers\FeedController();
$p1 = $feed->createPost(['author_id' => 1, 'author_name' => 'Ana', 'content' => 'Post 1']);
$p2 = $feed->createPost(['author_id' => 1, 'author_name' => 'Ana', 'content' => 'Post 2']);
if (!isset($p1['saved']['id']) || !isset($p2['saved']['id'])) {
  echo "feed advanced setup failed\n";
  exit(1);
}

$firstReaction = $feed->react(['post_id' => $p1['saved']['id'], 'user_id' => 2, 'type' => 'like']);
$secondReaction = $feed->react(['post_id' => $p1['saved']['id'], 'user_id' => 2, 'type' => 'love']);
if (($secondReaction['updated'] ?? false) !== true || ($secondReaction['saved']['type'] ?? '') !== 'love') {
  echo "reaction dedupe update failed\n";
  exit(1);
}

$removeReaction = $feed->removeReaction(['post_id' => $p1['saved']['id'], 'user_id' => 2]);
if (($removeReaction['removed'] ?? false) !== true) {
  echo "remove reaction failed\n";
  exit(1);
}

$c1 = $feed->comment(['post_id' => $p1['saved']['id'], 'user_id' => 3, 'comment' => 'Muito bom']);
$updatedComment = $feed->updateComment(['id' => $c1['saved']['id'], 'comment' => 'Excelente']);
if (($updatedComment['saved']['comment'] ?? '') !== 'Excelente') {
  echo "update comment failed\n";
  exit(1);
}

$page = $feed->index(['limit' => 1, 'offset' => 0]);
if (($page['meta']['returned'] ?? 0) !== 1 || ($page['meta']['has_more'] ?? false) !== true) {
  echo "pagination failed\n";
  exit(1);
}

$deletePost = $feed->deletePost(['id' => $p1['saved']['id']]);
if (($deletePost['deleted_id'] ?? 0) !== $p1['saved']['id']) {
  echo "delete post failed\n";
  exit(1);
}

echo "feed advanced test ok\n";
