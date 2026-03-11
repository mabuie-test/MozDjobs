<?php
require __DIR__.'/../app/Controllers/FavoriteController.php';
require __DIR__.'/../app/Controllers/NotificationController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$f = new App\Controllers\FavoriteController();
$r = $f->store(['user_id' => 7, 'entity_type' => 'job', 'entity_id' => 3]);
if (($r['resource'] ?? '') !== 'Favorite') {
  echo "favorites test failed\n";
  exit(1);
}

$n = new App\Controllers\NotificationController();
$created = $n->store(['user_id' => 7, 'title' => 'Nova proposta', 'body' => 'Recebeste uma proposta.']);
if (($created['saved']['read'] ?? true) !== false) {
  echo "notifications create test failed\n";
  exit(1);
}
$read = $n->markRead(['notification_id' => $created['saved']['id']]);
if (($read['saved']['read'] ?? false) !== true) {
  echo "notifications read test failed\n";
  exit(1);
}

echo "favorites/notifications test ok\n";
