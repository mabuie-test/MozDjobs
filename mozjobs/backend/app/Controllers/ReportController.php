<?php
namespace App\Controllers;

use App\Helpers\JsonStore;

class ReportController {
  public function overview(): array {
    $store = new JsonStore();
    $users = $store->all('users');
    $jobs = $store->all('jobs');
    $services = $store->all('services');
    $orders = $store->all('orders');
    $payments = $store->all('payments');
    $reviews = $store->all('reviews');

    $gmv = array_sum(array_map(fn($p) => (float)($p['amount'] ?? 0), $payments));
    $avgRating = count($reviews)
      ? round(array_sum(array_map(fn($r) => (int)($r['rating'] ?? 0), $reviews)) / count($reviews), 2)
      : 0;

    return [
      'users_total' => count($users),
      'jobs_total' => count($jobs),
      'services_total' => count($services),
      'orders_total' => count($orders),
      'payments_total' => count($payments),
      'gmv' => $gmv,
      'avg_rating' => $avgRating,
      'take_rate_projection_10pct' => round($gmv * 0.10, 2),
    ];
  }

  public function exportCsv(): array {
    $data = $this->overview();
    $rows = ["metric,value"];
    foreach ($data as $k => $v) {
      $rows[] = $k.','.$v;
    }

    return ['filename' => 'mozjobs-overview.csv', 'content' => implode("\n", $rows)];
  }
}
