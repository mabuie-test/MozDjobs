<?php
require __DIR__.'/../app/Controllers/JobController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$c = new App\Controllers\JobController();
$r = $c->index();
if (($r['resource'] ?? '') !== 'Job') {
  echo "jobs test failed\n";
  exit(1);
}
echo "jobs test ok\n";
