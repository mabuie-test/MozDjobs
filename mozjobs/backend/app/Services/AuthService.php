<?php
namespace App\Services;
class AuthService {
  public function hashPassword(string $password): string { return password_hash($password, PASSWORD_BCRYPT); }
  public function verifyPassword(string $password, string $hash): bool { return password_verify($password, $hash); }
  public function issueToken(array $payload): string {
    $body = base64_encode(json_encode($payload));
    $sig = hash_hmac('sha256', $body, getenv('JWT_SECRET') ?: 'secret');
    return $body.'.'.$sig;
  }
}
