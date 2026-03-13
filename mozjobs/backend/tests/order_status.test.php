<?php
require __DIR__.'/../app/Controllers/OrderController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$c = new App\Controllers\OrderController();
$created = $c->store(['client_id' => 1, 'professional_id' => 2, 'amount' => 500]);
if (!isset($created['saved']['id'])) {
  echo "order create test failed\n";
  exit(1);
}
$updated = $c->updateStatus(['order_id' => $created['saved']['id'], 'status' => 'in_progress']);
if (($updated['saved']['status'] ?? '') !== 'in_progress') {
  echo "order status test failed\n";
  exit(1);
}
echo "order status test ok\n";
