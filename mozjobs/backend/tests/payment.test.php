<?php
require __DIR__.'/../app/Controllers/PaymentController.php';
require __DIR__.'/../app/Helpers/JsonStore.php';
require __DIR__.'/../app/Helpers/Validator.php';
require __DIR__.'/../app/Services/MpesaService.php';
require __DIR__.'/../app/Services/EmolaService.php';
require __DIR__.'/../app/Services/MkeshService.php';

$c = new App\Controllers\PaymentController();
$r = $c->createEscrow(['order_id' => 1, 'provider' => 'mpesa', 'amount' => 1000]);
if (($r['saved']['escrow_status'] ?? '') !== 'held') {
  echo "payment escrow test failed\n";
  exit(1);
}
$released = $c->releaseEscrow(['payment_id' => $r['saved']['id']]);
if (($released['saved']['escrow_status'] ?? '') !== 'released') {
  echo "payment release test failed\n";
  exit(1);
}

echo "payment test ok\n";
