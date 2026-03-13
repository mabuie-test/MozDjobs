<?php
require __DIR__.'/../app/Controllers/PaymentController.php';
require __DIR__.'/../app/Controllers/OrderController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';
require __DIR__.'/../app/Services/MpesaService.php';
require __DIR__.'/../app/Services/EmolaService.php';
require __DIR__.'/../app/Services/MkeshService.php';

$orderController = new App\Controllers\OrderController();
$order = $orderController->store(['client_id' => 1, 'professional_id' => 2, 'amount' => 1000]);

$c = new App\Controllers\PaymentController();
$r = $c->createEscrow(['order_id' => $order['saved']['id'], 'provider' => 'mpesa', 'amount' => 1000]);
if (($r['saved']['escrow_status'] ?? '') !== 'held') {
  echo "payment escrow test failed\n";
  exit(1);
}

$blocked = $c->releaseEscrow(['payment_id' => $r['saved']['id']]);
if (($blocked['error'] ?? '') !== 'order must be completed before escrow release') {
  echo "payment release guard test failed\n";
  exit(1);
}

$orderController->updateStatus(['order_id' => $order['saved']['id'], 'status' => 'completed']);
$released = $c->releaseEscrow(['payment_id' => $r['saved']['id']]);
if (($released['saved']['escrow_status'] ?? '') !== 'released') {
  echo "payment release test failed\n";
  exit(1);
}

echo "payment test ok\n";
