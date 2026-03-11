<?php
namespace App\Services;

class AuthService {
  public function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  public function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
  }

  public function issueToken(array $payload): string {
    $payload['exp'] = time() + 86400;
    $body = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
    $signature = hash_hmac('sha256', $body, getenv('JWT_SECRET') ?: 'secret');
    return $body.'.'.$signature;
  }

  public function parseToken(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 2) {
      return null;
    }

    [$body, $signature] = $parts;
    $expected = hash_hmac('sha256', $body, getenv('JWT_SECRET') ?: 'secret');
    if (!hash_equals($expected, $signature)) {
      return null;
    }

    $decoded = json_decode(base64_decode(strtr($body, '-_', '+/')), true);
    if (!is_array($decoded)) {
      return null;
    }

    if (($decoded['exp'] ?? 0) < time()) {
      return null;
    }

    return $decoded;
  }
}
