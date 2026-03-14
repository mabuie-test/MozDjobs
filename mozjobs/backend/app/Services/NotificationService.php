<?php
namespace App\Services;

use App\Helpers\Logger;

class NotificationService {
  public function send(string $channel, string $msg): bool {
    if (!in_array($channel, ['in_app', 'sms', 'email'], true)) {
      Logger::info("notification.invalid_channel channel={$channel}");
      return false;
    }

    Logger::info("notification.sent channel={$channel} message=".substr($msg, 0, 120));
    return true;
  }
}
