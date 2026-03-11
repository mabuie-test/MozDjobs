<?php
return [
  'GET /api/health' => ['handler' => fn() => ['status' => 'ok']],

  'POST /api/auth/register' => ['handler' => ['App\\Controllers\\AuthController', 'register']],
  'POST /api/auth/login' => ['handler' => ['App\\Controllers\\AuthController', 'login']],

  'GET /api/users' => ['handler' => ['App\\Controllers\\UserController', 'index'], 'auth' => true, 'role' => ['admin']],

  'GET /api/profiles' => ['handler' => ['App\\Controllers\\ProfileController', 'index'], 'auth' => true],
  'POST /api/profiles' => ['handler' => ['App\\Controllers\\ProfileController', 'store'], 'auth' => true],

  'GET /api/jobs' => ['handler' => ['App\\Controllers\\JobController', 'index']],
  'POST /api/jobs' => ['handler' => ['App\\Controllers\\JobController', 'store'], 'auth' => true, 'role' => ['company', 'admin']],

  'GET /api/services' => ['handler' => ['App\\Controllers\\ServiceController', 'index']],
  'POST /api/services' => ['handler' => ['App\\Controllers\\ServiceController', 'store'], 'auth' => true, 'role' => ['professional', 'admin']],

  'GET /api/orders' => ['handler' => ['App\\Controllers\\OrderController', 'index'], 'auth' => true],
  'POST /api/orders' => ['handler' => ['App\\Controllers\\OrderController', 'store'], 'auth' => true],

  'GET /api/payments' => ['handler' => ['App\\Controllers\\PaymentController', 'index'], 'auth' => true, 'role' => ['admin']],
  'POST /api/payments/escrow' => ['handler' => ['App\\Controllers\\PaymentController', 'createEscrow'], 'auth' => true],
  'POST /api/payments/release' => ['handler' => ['App\\Controllers\\PaymentController', 'releaseEscrow'], 'auth' => true, 'role' => ['admin']],

  'GET /api/reviews' => ['handler' => ['App\\Controllers\\ReviewController', 'index']],
  'POST /api/reviews' => ['handler' => ['App\\Controllers\\ReviewController', 'store'], 'auth' => true],

  'GET /api/chat' => ['handler' => ['App\\Controllers\\ChatController', 'index'], 'auth' => true],
  'POST /api/chat' => ['handler' => ['App\\Controllers\\ChatController', 'store'], 'auth' => true],

  'GET /api/admin/metrics' => ['handler' => ['App\\Controllers\\AdminController', 'metrics'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/users/ban' => ['handler' => ['App\\Controllers\\AdminController', 'banUser'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/jobs/approve' => ['handler' => ['App\\Controllers\\AdminController', 'approveJob'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/services/approve' => ['handler' => ['App\\Controllers\\AdminController', 'approveService'], 'auth' => true, 'role' => ['admin']],
];
