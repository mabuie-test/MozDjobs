<?php
namespace App\Controllers;

use App\Helpers\JsonStore;

class AdminController {
  public function metrics(): array {
    $store = new JsonStore();
    $payments = $store->all('payments');
    $orders = $store->all('orders');
    $disputes = $store->all('disputes');

    return [
      'users_total' => count($store->all('users')),
      'jobs_total' => count($store->all('jobs')),
      'services_total' => count($store->all('services')),
      'orders_total' => count($orders),
      'payments_total' => count($payments),
      'disputes_open' => count(array_filter($disputes, fn($d) => ($d['status'] ?? '') === 'open')),
      'gmv' => array_sum(array_map(fn($p) => (float) ($p['amount'] ?? 0), $payments)),
      'completed_orders' => count(array_filter($orders, fn($o) => ($o['status'] ?? '') === 'completed'))
    ];
  }

  public function banUser(array $data): array {
    $id = (int)($data['id'] ?? 0);
    $updated = (new JsonStore())->update('users', $id, function ($user) {
      $user['status'] = 'banned';
      return $user;
    });
    return $updated ? ['message' => 'user banned', 'user' => $updated] : ['error' => 'user not found'];
  }

  public function approveJob(array $data): array {
    $id = (int)($data['id'] ?? 0);
    $updated = (new JsonStore())->update('jobs', $id, function ($job) {
      $job['approved'] = true;
      return $job;
    });
    return $updated ? ['message' => 'job approved', 'job' => $updated] : ['error' => 'job not found'];
  }

  public function approveService(array $data): array {
    $id = (int)($data['id'] ?? 0);
    $updated = (new JsonStore())->update('services', $id, function ($service) {
      $service['approved'] = true;
      return $service;
    });
    return $updated ? ['message' => 'service approved', 'service' => $updated] : ['error' => 'service not found'];
  }
}
