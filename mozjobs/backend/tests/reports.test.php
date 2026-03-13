<?php
require __DIR__.'/../app/Controllers/ReportController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';

$controller = new App\Controllers\ReportController();
$overview = $controller->overview();
if (!isset($overview['gmv']) || !isset($overview['take_rate_projection_10pct'])) {
  echo "reports overview test failed\n";
  exit(1);
}
$csv = $controller->exportCsv();
if (!str_contains(($csv['content'] ?? ''), 'metric,value')) {
  echo "reports csv test failed\n";
  exit(1);
}
echo "reports test ok\n";
