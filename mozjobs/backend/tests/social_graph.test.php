<?php
require __DIR__.'/../app/Controllers/StoryController.php';
require __DIR__.'/../app/Controllers/FollowController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$story = new App\Controllers\StoryController();
$savedStory = $story->store(['user_id' => 1, 'user_name' => 'Ana', 'text' => 'Disponível para projetos!']);
if (!isset($savedStory['saved']['id'])) {
  echo "story test failed\n";
  exit(1);
}

$follow = new App\Controllers\FollowController();
$savedFollow = $follow->store(['follower_id' => 1, 'followed_id' => 5]);
if (!isset($savedFollow['saved']['id'])) {
  echo "follow test failed\n";
  exit(1);
}

echo "social graph test ok\n";
