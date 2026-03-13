<?php
require __DIR__.'/../app/Controllers/OrderController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';

$store = new App\Helpers\JsonStore();
$store->saveAll('orders', []);
$store->saveAll('order_timeline', []);
$store->saveAll('notifications', []);

$orderController = new App\Controllers\OrderController();
$order = $orderController->store(['client_id' => 10, 'professional_id' => 20, 'amount' => 700]);
if (!isset($order['saved']['id'])) {
  echo "delivery setup failed\n";
  exit(1);
}

$submit = $orderController->submitDelivery([
  'order_id' => $order['saved']['id'],
  'professional_id' => 20,
  'notes' => 'Versão final entregue'
]);
if (($submit['saved']['delivery_status'] ?? '') !== 'submitted') {
  echo "submit delivery failed\n";
  exit(1);
}

$review = $orderController->reviewDelivery([
  'order_id' => $order['saved']['id'],
  'client_id' => 10,
  'decision' => 'accept',
  'notes' => 'Tudo conforme acordado'
]);
if (($review['saved']['status'] ?? '') !== 'completed') {
  echo "review delivery failed\n";
  exit(1);
}

$timeline = $orderController->timeline(['order_id' => $order['saved']['id']]);
if (count($timeline['items'] ?? []) < 3) {
  echo "order timeline failed\n";
  exit(1);
}

echo "delivery workflow test ok\n";
