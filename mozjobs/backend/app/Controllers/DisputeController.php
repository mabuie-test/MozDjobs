<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;

class DisputeController {
  public function index(): array {
    return ['resource' => 'Dispute', 'items' => (new JsonStore())->all('disputes')];
  }

  public function store(array $data): array {
    $missing = Validator::requireFields($data, ['order_id', 'opened_by', 'reason']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $dispute = (new JsonStore())->create('disputes', [
      'order_id' => (int) $data['order_id'],
      'opened_by' => (int) $data['opened_by'],
      'reason' => trim((string) $data['reason']),
      'status' => 'open'
    ]);

    (new JsonStore())->update('orders', (int) $data['order_id'], function ($order) {
      $order['status'] = 'disputed';
      return $order;
    });

    return ['resource' => 'Dispute', 'saved' => $dispute];
  }

  public function resolve(array $data): array {
    $missing = Validator::requireFields($data, ['dispute_id', 'resolution']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $resolved = (new JsonStore())->update('disputes', (int) $data['dispute_id'], function ($dispute) use ($data) {
      $dispute['status'] = 'resolved';
      $dispute['resolution'] = trim((string) $data['resolution']);
      $dispute['resolved_at'] = date('c');
      return $dispute;
    });

    return $resolved ? ['resource' => 'Dispute', 'saved' => $resolved] : ['error' => 'dispute not found'];
  }
}
