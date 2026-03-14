<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;
use App\Services\AuthService;

class AuthController {
  private const MAX_FAILED_ATTEMPTS = 5;
  private const LOCK_SECONDS = 900;

  public function register(array $input): array {
    $missing = Validator::requireFields($input, ['name', 'email', 'password', 'role']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    if (!Validator::email($input['email'])) {
      return ['error' => 'invalid email'];
    }

    if (!Validator::minLength((string) $input['password'], 6)) {
      return ['error' => 'password must have at least 6 chars'];
    }

    $store = new JsonStore();
    if ($store->findBy('users', 'email', $input['email'])) {
      return ['error' => 'email already exists'];
    }

    $auth = new AuthService();
    $created = $store->create('users', [
      'name' => $input['name'],
      'email' => $input['email'],
      'password_hash' => $auth->hashPassword($input['password']),
      'role' => $input['role'],
      'status' => 'active'
    ]);

    unset($created['password_hash']);
    return ['message' => 'registered', 'user' => $created];
  }

  public function login(array $input): array {
    $missing = Validator::requireFields($input, ['email', 'password']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $emailKey = strtolower((string) ($input['email'] ?? ''));
    if ($this->isTemporarilyLocked($emailKey)) {
      return ['error' => 'too many attempts, try later'];
    }

    $store = new JsonStore();
    $user = $store->findBy('users', 'email', $input['email']);
    if (!$user || !isset($user['password_hash'])) {
      $this->registerFailedAttempt($emailKey);
      return ['error' => 'invalid credentials'];
    }

    $auth = new AuthService();
    if (!$auth->verifyPassword($input['password'], $user['password_hash'])) {
      $this->registerFailedAttempt($emailKey);
      return ['error' => 'invalid credentials'];
    }

    $this->clearAttempts($emailKey);

    $token = $auth->issueToken([
      'id' => $user['id'],
      'email' => $user['email'],
      'role' => $user['role']
    ]);

    return ['token' => $token, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']]];
  }

  private function isTemporarilyLocked(string $key): bool {
    if ($key === '') return false;

    $state = $this->readThrottleState();
    $entry = $state[$key] ?? ['count' => 0, 'last' => 0];
    if (($entry['count'] ?? 0) < self::MAX_FAILED_ATTEMPTS) {
      return false;
    }

    return (time() - (int) ($entry['last'] ?? 0)) < self::LOCK_SECONDS;
  }

  private function registerFailedAttempt(string $key): void {
    if ($key === '') return;

    $state = $this->readThrottleState();
    $entry = $state[$key] ?? ['count' => 0, 'last' => 0];
    $entry['count'] = ((int) ($entry['count'] ?? 0)) + 1;
    $entry['last'] = time();
    $state[$key] = $entry;
    $this->writeThrottleState($state);
  }

  private function clearAttempts(string $key): void {
    if ($key === '') return;

    $state = $this->readThrottleState();
    unset($state[$key]);
    $this->writeThrottleState($state);
  }

  private function readThrottleState(): array {
    $path = $this->throttleFilePath();
    if (!file_exists($path)) {
      return [];
    }

    $decoded = json_decode((string) file_get_contents($path), true);
    return is_array($decoded) ? $decoded : [];
  }

  private function writeThrottleState(array $state): void {
    $path = $this->throttleFilePath();
    $dir = dirname($path);
    if (!is_dir($dir)) {
      @mkdir($dir, 0777, true);
    }

    file_put_contents($path, json_encode($state));
  }

  private function throttleFilePath(): string {
    return __DIR__.'/../../storage/login_throttle.json';
  }
}
