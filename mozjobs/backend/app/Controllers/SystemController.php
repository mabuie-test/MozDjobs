<?php
namespace App\Controllers;

class SystemController {
  public function health(): array {
    return [
      'status' => 'ok',
      'service' => 'mozjobs-backend',
      'time' => date('c'),
    ];
  }

  public function ready(): array {
    $checks = [
      'php_version' => version_compare(PHP_VERSION, '8.1.0', '>=') ? 'ok' : 'fail',
      'openssl_ext' => extension_loaded('openssl') ? 'ok' : 'fail',
      'storage_writable' => is_writable(__DIR__.'/../../storage/data') ? 'ok' : 'fail',
      'jwt_secret' => strlen((string)(getenv('JWT_SECRET') ?: '')) >= 24 ? 'ok' : 'warn',
      'app_encryption_key' => strlen((string)(getenv('APP_ENCRYPTION_KEY') ?: getenv('APP_KEY') ?: '')) >= 24 ? 'ok' : 'warn',
    ];

    $status = in_array('fail', $checks, true) ? 'fail' : (in_array('warn', $checks, true) ? 'warn' : 'ok');

    return [
      'status' => $status,
      'checks' => $checks,
      'service' => 'mozjobs-backend',
      'time' => date('c'),
    ];
  }
}
