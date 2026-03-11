<?php
require __DIR__.'/../app/Controllers/AuthController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';
require __DIR__.'/../app/Services/AuthService.php';

$controller = new App\Controllers\AuthController();
$email = 'user'.time().'@mozjobs.mz';
$registered = $controller->register([
  'name' => 'Teste',
  'email' => $email,
  'password' => '123456',
  'role' => 'professional'
]);
if (($registered['message'] ?? '') !== 'registered') {
  echo "auth flow register failed\n";
  exit(1);
}
$login = $controller->login(['email' => $email, 'password' => '123456']);
if (!isset($login['token'])) {
  echo "auth flow login failed\n";
  exit(1);
}
echo "auth flow test ok\n";
