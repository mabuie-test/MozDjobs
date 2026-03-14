<?php
require __DIR__.'/../app/Services/AuthService.php';
$svc = new App\Services\AuthService();
$hash = $svc->hashPassword('123456');
if (!$svc->verifyPassword('123456', $hash)) { echo "auth test failed\n"; exit(1);} 
echo "auth test ok\n";
