<?php
namespace App\Services;
class MkeshService { public function charge(float $amount): array { return ['provider'=>'mkesh','amount'=>$amount,'status'=>'pending']; } }
