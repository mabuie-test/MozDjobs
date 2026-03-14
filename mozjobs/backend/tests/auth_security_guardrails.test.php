<?php
require __DIR__.'/../app/Controllers/AuthController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';
require __DIR__.'/../app/Services/AuthService.php';

$controller = new App\Controllers\AuthController();
$ref = new ReflectionClass($controller);
$throttleMethod = $ref->getMethod('throttleFilePath');
$throttleMethod->setAccessible(true);
$throttlePath = $throttleMethod->invoke($controller);

@unlink($throttlePath);

for ($i = 0; $i < 5; $i++) {
  $response = $controller->login(['email' => 'nobody@example.com', 'password' => 'wrong']);
  if (($response['error'] ?? '') !== 'invalid credentials') {
    echo "unexpected login response before lock\n";
    exit(1);
  }
}

$locked = $controller->login(['email' => 'nobody@example.com', 'password' => 'wrong']);
if (($locked['error'] ?? '') !== 'too many attempts, try later') {
  echo "lock protection failed\n";
  exit(1);
}

echo "auth security guardrails test ok\n";
