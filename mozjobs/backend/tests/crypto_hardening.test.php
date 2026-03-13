<?php
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Services/AuthService.php';

$dir = __DIR__.'/../storage/data';
$store = new App\Helpers\JsonStore($dir);
$store->saveAll('crypto_probe', [
  ['id' => 1, 'secret' => 'mensagem super confidencial', 'user_id' => 5]
]);

$file = $dir.'/crypto_probe.json';
$raw = file_get_contents($file) ?: '';
if (str_contains($raw, 'mensagem super confidencial')) {
  echo "encryption at rest failed\n";
  exit(1);
}

$items = $store->all('crypto_probe');
if (($items[0]['secret'] ?? '') !== 'mensagem super confidencial') {
  echo "decryption readback failed\n";
  exit(1);
}

$auth = new App\Services\AuthService();
$token = $auth->issueToken(['id' => 11, 'role' => 'admin']);
$parts = explode('.', $token);
if (count($parts) !== 3) {
  echo "jwt format hardening failed\n";
  exit(1);
}
$parsed = $auth->parseToken($token);
if (($parsed['id'] ?? 0) !== 11 || ($parsed['role'] ?? '') !== 'admin' || !isset($parsed['jti'])) {
  echo "jwt claims hardening failed\n";
  exit(1);
}

echo "crypto hardening test ok\n";
