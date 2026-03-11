<?php
namespace App\Middleware;
class RateLimitMiddleware {
  public function handle(string $key): bool {
    $file = sys_get_temp_dir().'/mozjobs_rate_'.md5($key);
    $count = (int)@file_get_contents($file);
    $count++;
    file_put_contents($file, (string)$count);
    return $count <= 200;
  }
}
