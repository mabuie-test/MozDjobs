<?php
require __DIR__.'/../app/Controllers/DisputeController.php';
require __DIR__.'/../app/Controllers/OrderController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$orderController = new App\Controllers\OrderController();
$order = $orderController->store(['client_id' => 1, 'professional_id' => 2, 'amount' => 900]);
if (!isset($order['saved']['id'])) {
  echo "dispute setup failed\n";
  exit(1);
}

$disputeController = new App\Controllers\DisputeController();
$opened = $disputeController->store(['order_id' => $order['saved']['id'], 'opened_by' => 1, 'reason' => 'Entrega incompleta']);
if (($opened['saved']['status'] ?? '') !== 'open') {
  echo "dispute open failed\n";
  exit(1);
}

$resolved = $disputeController->resolve(['dispute_id' => $opened['saved']['id'], 'resolution' => 'Reembolso parcial']);
if (($resolved['saved']['status'] ?? '') !== 'resolved') {
  echo "dispute resolve failed\n";
  exit(1);
}

echo "dispute flow test ok\n";
