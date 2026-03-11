<?php
require __DIR__.'/../app/Controllers/UserController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';

$c = new App\Controllers\UserController();
$r = $c->store(['name' => 'Ana']);
if (($r['saved']['name'] ?? '') !== 'Ana') {
  echo "users test failed\n";
  exit(1);
}
echo "users test ok\n";
