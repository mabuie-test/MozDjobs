<?php
namespace App\Services;
class EmolaService { public function charge(float $amount): array { return ['provider'=>'emola','amount'=>$amount,'status'=>'pending']; } }
