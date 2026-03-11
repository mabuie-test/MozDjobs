<?php
namespace App\Helpers;
class Logger {
  public static function info(string $message): void {
    file_put_contents(__DIR__.'/../../storage/logs/app.log', date('c')." INFO {$message}\n", FILE_APPEND);
  }
}
