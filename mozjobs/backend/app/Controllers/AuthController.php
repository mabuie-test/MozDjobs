<?php
namespace App\Controllers;
use App\Helpers\Validator;
use App\Services\AuthService;
class AuthController {
  public function register(array $input): array {
    $missing = Validator::requireFields($input, ['name','email','password']);
    if ($missing) return ['error'=>'missing: '.implode(',',$missing)];
    $auth = new AuthService();
    return ['message'=>'registered','password_hash'=>$auth->hashPassword($input['password'])];
  }
  public function login(array $input): array {
    $missing = Validator::requireFields($input, ['email','password']);
    if ($missing) return ['error'=>'missing: '.implode(',',$missing)];
    $token = (new AuthService())->issueToken(['email'=>$input['email'],'role'=>'professional']);
    return ['token'=>$token];
  }
}
