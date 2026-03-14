<?php
require __DIR__.'/../app/Controllers/SystemController.php';

$ctrl = new App\Controllers\SystemController();
$health = $ctrl->health();
if (($health['status'] ?? '') !== 'ok') {
  echo "health endpoint failed\n";
  exit(1);
}

$ready = $ctrl->ready();
if (!isset($ready['checks']) || !is_array($ready['checks'])) {
  echo "readiness checks missing\n";
  exit(1);
}
if (!isset($ready['checks']['openssl_ext']) || !isset($ready['checks']['storage_writable'])) {
  echo "readiness core checks missing\n";
  exit(1);
}

echo "production readiness test ok\n";
