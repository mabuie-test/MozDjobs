<?php
require __DIR__.'/../app/Controllers/OrderController.php';
require __DIR__.'/../app/Controllers/PaymentController.php';
require __DIR__.'/../app/Controllers/NotificationController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';
require __DIR__.'/../app/Services/MpesaService.php';
require __DIR__.'/../app/Services/EmolaService.php';
require __DIR__.'/../app/Services/MkeshService.php';

$store = new App\Helpers\JsonStore();
$store->saveAll('orders', []);
$store->saveAll('payments', []);
$store->saveAll('notifications', []);

$orderController = new App\Controllers\OrderController();
$invalidOrder = $orderController->store(['client_id' => 1, 'professional_id' => 2, 'amount' => 0]);
if (($invalidOrder['error'] ?? '') !== 'invalid amount') {
  echo "order amount validation failed\n";
  exit(1);
}

$order = $orderController->store(['client_id' => 1, 'professional_id' => 2, 'amount' => 1500, 'status' => 'open']);
$order2 = $orderController->store(['client_id' => 1, 'professional_id' => 3, 'amount' => 500, 'status' => 'in_progress']);
$filtered = $orderController->index(['client_id' => 1, 'status' => 'in_progress']);
if (count($filtered['items'] ?? []) !== 1) {
  echo "order filters failed\n";
  exit(1);
}

$paymentController = new App\Controllers\PaymentController();
$invalidPayment = $paymentController->createEscrow(['order_id' => $order['saved']['id'], 'provider' => 'mpesa', 'amount' => -1]);
if (($invalidPayment['error'] ?? '') !== 'invalid amount') {
  echo "payment amount validation failed\n";
  exit(1);
}

$escrow = $paymentController->createEscrow(['order_id' => $order['saved']['id'], 'provider' => 'mpesa', 'amount' => 1500]);
$duplicateEscrow = $paymentController->createEscrow(['order_id' => $order['saved']['id'], 'provider' => 'mpesa', 'amount' => 1500]);
if (($duplicateEscrow['error'] ?? '') !== 'escrow already held for order') {
  echo "duplicate escrow guard failed\n";
  exit(1);
}

$blockedRelease = $paymentController->releaseEscrow(['payment_id' => $escrow['saved']['id']]);
if (($blockedRelease['error'] ?? '') !== 'order must be completed before escrow release') {
  echo "release completion guard failed\n";
  exit(1);
}

$orderController->updateStatus(['order_id' => $order['saved']['id'], 'status' => 'completed']);
$released = $paymentController->releaseEscrow(['payment_id' => $escrow['saved']['id']]);
$releasedAgain = $paymentController->releaseEscrow(['payment_id' => $escrow['saved']['id']]);
if (($releasedAgain['error'] ?? '') !== 'escrow already released') {
  echo "idempotent release guard failed\n";
  exit(1);
}

$notificationController = new App\Controllers\NotificationController();
$n1 = $notificationController->store(['user_id' => 1, 'title' => 'A', 'body' => 'B']);
$n2 = $notificationController->store(['user_id' => 1, 'title' => 'C', 'body' => 'D']);
$notificationController->markRead(['notification_id' => $n1['saved']['id'], 'auth_user' => ['id' => 1, 'role' => 'professional']]);

$unread = $notificationController->index(['user_id' => 1, 'unread_only' => '1']);
if (count($unread['items'] ?? []) !== 1) {
  echo "notification unread filter failed\n";
  exit(1);
}

$forbiddenRead = $notificationController->markRead([
  'notification_id' => $n2['saved']['id'],
  'auth_user' => ['id' => 77, 'role' => 'professional']
]);
if (($forbiddenRead['error'] ?? '') !== 'forbidden') {
  echo "notification ownership failed\n";
  exit(1);
}

echo "orders/payments/notifications hardening test ok\n";
