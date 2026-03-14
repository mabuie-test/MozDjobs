<?php
require __DIR__.'/../app/Controllers/FeedController.php';
require __DIR__.'/../app/Controllers/FollowController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$store = new App\Helpers\JsonStore();
$store->saveAll('feed_posts', []);
$store->saveAll('feed_comments', []);
$store->saveAll('feed_reactions', []);
$store->saveAll('follows', []);

$feed = new App\Controllers\FeedController();
$post = $feed->createPost([
  'author_id' => 1,
  'author_name' => 'Ana',
  'content' => 'Post seguro',
  'auth_user' => ['id' => 1, 'role' => 'professional']
]);

$forbiddenUpdate = $feed->updatePost([
  'id' => $post['saved']['id'],
  'content' => 'hack',
  'auth_user' => ['id' => 2, 'role' => 'professional']
]);
if (($forbiddenUpdate['error'] ?? '') !== 'forbidden') {
  echo "feed ownership update failed\n";
  exit(1);
}

$comment = $feed->comment([
  'post_id' => $post['saved']['id'],
  'user_id' => 1,
  'comment' => 'ok',
  'auth_user' => ['id' => 1, 'role' => 'professional']
]);

$forbiddenCommentDelete = $feed->deleteComment([
  'id' => $comment['saved']['id'],
  'auth_user' => ['id' => 3, 'role' => 'professional']
]);
if (($forbiddenCommentDelete['error'] ?? '') !== 'forbidden') {
  echo "comment ownership delete failed\n";
  exit(1);
}

$follow = new App\Controllers\FollowController();
$forbiddenFollow = $follow->store([
  'follower_id' => 1,
  'followed_id' => 2,
  'auth_user' => ['id' => 3, 'role' => 'professional']
]);
if (($forbiddenFollow['error'] ?? '') !== 'forbidden') {
  echo "follow ownership failed\n";
  exit(1);
}

$adminFollow = $follow->store([
  'follower_id' => 1,
  'followed_id' => 2,
  'auth_user' => ['id' => 99, 'role' => 'admin']
]);
if (!isset($adminFollow['saved']['id'])) {
  echo "admin override failed\n";
  exit(1);
}

echo "social permissions test ok\n";
