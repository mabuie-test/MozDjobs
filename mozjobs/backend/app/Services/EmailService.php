<?php
namespace App\Services;

use App\Helpers\Logger;

class EmailService {
  public function send(string $to, string $subject, string $body): bool {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
      Logger::info("email.invalid_recipient to={$to}");
      return false;
    }

    Logger::info("email.sent to={$to} subject=".substr($subject, 0, 80)." body_len=".strlen($body));
    return true;
  }
}
