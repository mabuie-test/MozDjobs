<?php
namespace App\Middleware;
class AuthMiddleware {
  public function handle(): bool { return isset($_SERVER['HTTP_AUTHORIZATION']); }
}
