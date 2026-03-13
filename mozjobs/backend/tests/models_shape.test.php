<?php
require __DIR__.'/../app/Models/User.php';
require __DIR__.'/../app/Models/Job.php';

$user = new App\Models\User(id: 1, name: 'Ana', email: 'ana@mozjobs.mz', role: 'professional');
$job = new App\Models\Job(id: 4, company_id: 2, title: 'Backend PHP', description: 'API dev');

if (($user->toArray()['name'] ?? '') !== 'Ana') {
  echo "model user shape test failed\n";
  exit(1);
}

if (($job->toArray()['title'] ?? '') !== 'Backend PHP') {
  echo "model job shape test failed\n";
  exit(1);
}

echo "models shape test ok\n";
