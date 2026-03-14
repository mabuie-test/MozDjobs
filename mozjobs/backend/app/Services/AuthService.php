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
    $now = time();
    $claims = array_merge($payload, [
      'iss' => getenv('APP_URL') ?: 'mozjobs',
      'aud' => 'mozjobs-clients',
      'iat' => $now,
      'nbf' => $now,
      'exp' => $now + 86400,
      'jti' => bin2hex(random_bytes(12)),
    ]);

    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $encodedHeader = $this->b64urlEncode(json_encode($header) ?: '{}');
    $encodedPayload = $this->b64urlEncode(json_encode($claims) ?: '{}');
    $signingInput = $encodedHeader.'.'.$encodedPayload;
    $signature = hash_hmac('sha256', $signingInput, $this->secret(), true);

    return $signingInput.'.'.$this->b64urlEncode($signature);
  }

  public function parseToken(string $token): ?array {
    $parts = explode('.', $token);

    if (count($parts) === 2) {
      return $this->parseLegacyToken($parts[0], $parts[1]);
    }

    if (count($parts) !== 3) {
      return null;
    }

    [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
    $signingInput = $encodedHeader.'.'.$encodedPayload;
    $expected = hash_hmac('sha256', $signingInput, $this->secret(), true);
    $provided = $this->b64urlDecode($encodedSignature);
    if (!is_string($provided) || !hash_equals($expected, $provided)) {
      return null;
    }

    $decoded = json_decode($this->b64urlDecode($encodedPayload) ?: '', true);
    if (!is_array($decoded)) {
      return null;
    }

    $now = time();
    $allowedSkew = 30;
    if (($decoded['nbf'] ?? 0) > ($now + $allowedSkew)) return null;
    if (($decoded['exp'] ?? 0) < ($now - $allowedSkew)) return null;

    return $decoded;
  }

  private function parseLegacyToken(string $body, string $signature): ?array {
    $expected = hash_hmac('sha256', $body, $this->secret());
    if (!hash_equals($expected, $signature)) return null;

    $decoded = json_decode(base64_decode(strtr($body, '-_', '+/')), true);
    if (!is_array($decoded)) return null;
    if (($decoded['exp'] ?? 0) < time()) return null;
    return $decoded;
  }

  private function secret(): string {
    return getenv('JWT_SECRET') ?: 'secret';
  }

  private function b64urlEncode(string $value): string {
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
  }

  private function b64urlDecode(string $value): string|false {
    $padding = strlen($value) % 4;
    if ($padding > 0) {
      $value .= str_repeat('=', 4 - $padding);
    }
    return base64_decode(strtr($value, '-_', '+/'), true);
  }
}
