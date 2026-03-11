<?php
namespace App\Middleware;
class ErrorHandler {
  public static function json(string $message, int $code=400): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['error'=>$message]);
  }
}
