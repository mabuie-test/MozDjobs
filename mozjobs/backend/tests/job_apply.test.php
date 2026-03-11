<?php
require __DIR__.'/../app/Controllers/JobController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$c = new App\Controllers\JobController();
$r = $c->apply(['job_id' => 1, 'professional_id' => 12, 'cover_letter' => 'Tenho experiência.']);
if (($r['resource'] ?? '') !== 'Application') {
  echo "job apply test failed\n";
  exit(1);
}
echo "job apply test ok\n";
