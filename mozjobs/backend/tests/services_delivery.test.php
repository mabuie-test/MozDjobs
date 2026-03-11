<?php
require __DIR__.'/../app/Helpers/Logger.php';
require __DIR__.'/../app/Services/EmailService.php';
require __DIR__.'/../app/Services/NotificationService.php';

$email = new App\Services\EmailService();
$notification = new App\Services\NotificationService();

if (!$email->send('ops@mozjobs.mz', 'Teste', 'Mensagem de teste')) {
  echo "email service test failed\n";
  exit(1);
}

if (!$notification->send('in_app', 'Nova candidatura recebida')) {
  echo "notification service test failed\n";
  exit(1);
}

echo "services delivery test ok\n";
