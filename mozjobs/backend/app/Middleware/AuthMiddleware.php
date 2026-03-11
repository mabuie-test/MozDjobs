<?php
namespace App\Middleware;

use App\Services\AuthService;

class AuthMiddleware {
  public function userFromRequest(): ?array {
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!str_starts_with($header, 'Bearer ')) {
      return null;
    }

    $token = trim(substr($header, 7));
    return (new AuthService())->parseToken($token);
  }
}
