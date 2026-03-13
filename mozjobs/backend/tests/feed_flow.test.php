<?php
require __DIR__.'/../app/Controllers/FeedController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$feed = new App\Controllers\FeedController();
$post = $feed->createPost(['author_id' => 1, 'author_name' => 'Ana', 'content' => 'Nova oportunidade PHP']);
if (!isset($post['saved']['id'])) {
  echo "feed post test failed\n";
  exit(1);
}
$reaction = $feed->react(['post_id' => $post['saved']['id'], 'user_id' => 2, 'type' => 'like']);
$comment = $feed->comment(['post_id' => $post['saved']['id'], 'user_id' => 3, 'comment' => 'Interessante!']);
if (!isset($reaction['saved']['id']) || !isset($comment['saved']['id'])) {
  echo "feed interaction test failed\n";
  exit(1);
}
$timeline = $feed->index();
if (($timeline['resource'] ?? '') !== 'FeedPost') {
  echo "feed index test failed\n";
  exit(1);
}

echo "feed flow test ok\n";
