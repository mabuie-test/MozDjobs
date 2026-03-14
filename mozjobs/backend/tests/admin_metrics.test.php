<?php
require __DIR__.'/../app/Controllers/AdminController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';

$c = new App\Controllers\AdminController();
$m = $c->metrics();
if (!isset($m['users_total']) || !isset($m['disputes_open']) || !isset($m['gmv'])) {
  echo "admin metrics test failed\n";
  exit(1);
}

echo "admin metrics test ok\n";
