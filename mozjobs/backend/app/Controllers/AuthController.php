<?php
namespace App\Controllers;

use App\Helpers\JsonStore;
use App\Helpers\Validator;
use App\Services\AuthService;

class AuthController {
  public function register(array $input): array {
    $missing = Validator::requireFields($input, ['name', 'email', 'password', 'role']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    if (!Validator::email($input['email'])) {
      return ['error' => 'invalid email'];
    }

    if (!Validator::minLength((string) $input['password'], 6)) {
      return ['error' => 'password must have at least 6 chars'];
    }

    $store = new JsonStore();
    if ($store->findBy('users', 'email', $input['email'])) {
      return ['error' => 'email already exists'];
    }

    $auth = new AuthService();
    $created = $store->create('users', [
      'name' => $input['name'],
      'email' => $input['email'],
      'password_hash' => $auth->hashPassword($input['password']),
      'role' => $input['role'],
      'status' => 'active'
    ]);

    unset($created['password_hash']);
    return ['message' => 'registered', 'user' => $created];
  }

  public function login(array $input): array {
    $missing = Validator::requireFields($input, ['email', 'password']);
    if ($missing) {
      return ['error' => 'missing: '.implode(',', $missing)];
    }

    $store = new JsonStore();
    $user = $store->findBy('users', 'email', $input['email']);
    if (!$user || !isset($user['password_hash'])) {
      return ['error' => 'invalid credentials'];
    }

    $auth = new AuthService();
    if (!$auth->verifyPassword($input['password'], $user['password_hash'])) {
      return ['error' => 'invalid credentials'];
    }

    $token = $auth->issueToken([
      'id' => $user['id'],
      'email' => $user['email'],
      'role' => $user['role']
    ]);

    return ['token' => $token, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'role' => $user['role']]];
  }
}
