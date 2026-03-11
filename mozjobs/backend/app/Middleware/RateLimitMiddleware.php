<?php
namespace App\Middleware;

class RateLimitMiddleware {
  public function handle(string $key, int $max = 300): bool {
    $window = date('YmdHi');
    $file = sys_get_temp_dir().'/mozjobs_rate_'.md5($window.$key);
    $count = (int) @file_get_contents($file);
    $count++;
    file_put_contents($file, (string) $count);
    return $count <= $max;
  }
}
