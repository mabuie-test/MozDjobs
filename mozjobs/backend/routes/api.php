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
  'POST /api/jobs/apply' => ['handler' => ['App\\Controllers\\JobController', 'apply'], 'auth' => true, 'role' => ['professional', 'admin']],

  'GET /api/services' => ['handler' => ['App\\Controllers\\ServiceController', 'index']],
  'POST /api/services' => ['handler' => ['App\\Controllers\\ServiceController', 'store'], 'auth' => true, 'role' => ['professional', 'admin']],

  'GET /api/orders' => ['handler' => ['App\\Controllers\\OrderController', 'index'], 'auth' => true],
  'POST /api/orders' => ['handler' => ['App\\Controllers\\OrderController', 'store'], 'auth' => true],
  'POST /api/orders/status' => ['handler' => ['App\\Controllers\\OrderController', 'updateStatus'], 'auth' => true],

  'GET /api/payments' => ['handler' => ['App\\Controllers\\PaymentController', 'index'], 'auth' => true, 'role' => ['admin']],
  'POST /api/payments/escrow' => ['handler' => ['App\\Controllers\\PaymentController', 'createEscrow'], 'auth' => true],
  'POST /api/payments/release' => ['handler' => ['App\\Controllers\\PaymentController', 'releaseEscrow'], 'auth' => true, 'role' => ['admin']],

  'GET /api/reviews' => ['handler' => ['App\\Controllers\\ReviewController', 'index']],
  'GET /api/reviews/summary' => ['handler' => ['App\\Controllers\\ReviewController', 'summary']],
  'POST /api/reviews' => ['handler' => ['App\\Controllers\\ReviewController', 'store'], 'auth' => true],


  'GET /api/disputes' => ['handler' => ['App\\Controllers\\DisputeController', 'index'], 'auth' => true],
  'POST /api/disputes' => ['handler' => ['App\\Controllers\\DisputeController', 'store'], 'auth' => true],
  'GET /api/chat' => ['handler' => ['App\\Controllers\\ChatController', 'index'], 'auth' => true],
  'POST /api/chat' => ['handler' => ['App\\Controllers\\ChatController', 'store'], 'auth' => true],




  'GET /api/stories' => ['handler' => ['App\Controllers\StoryController', 'index'], 'auth' => true],
  'POST /api/stories' => ['handler' => ['App\Controllers\StoryController', 'store'], 'auth' => true],

  'GET /api/follows' => ['handler' => ['App\Controllers\FollowController', 'index'], 'auth' => true],
  'POST /api/follows' => ['handler' => ['App\Controllers\FollowController', 'store'], 'auth' => true],

  'GET /api/feed' => ['handler' => ['App\Controllers\FeedController', 'index'], 'auth' => true],
  'POST /api/feed/posts' => ['handler' => ['App\Controllers\FeedController', 'createPost'], 'auth' => true],
  'POST /api/feed/reactions' => ['handler' => ['App\Controllers\FeedController', 'react'], 'auth' => true],
  'POST /api/feed/reactions/remove' => ['handler' => ['App\Controllers\FeedController', 'removeReaction'], 'auth' => true],
  'POST /api/feed/comments' => ['handler' => ['App\Controllers\FeedController', 'comment'], 'auth' => true],
  'POST /api/feed/comments/update' => ['handler' => ['App\Controllers\FeedController', 'updateComment'], 'auth' => true],
  'POST /api/feed/comments/delete' => ['handler' => ['App\Controllers\FeedController', 'deleteComment'], 'auth' => true],
  'POST /api/feed/posts/update' => ['handler' => ['App\Controllers\FeedController', 'updatePost'], 'auth' => true],
  'POST /api/feed/posts/delete' => ['handler' => ['App\Controllers\FeedController', 'deletePost'], 'auth' => true],

  'GET /api/favorites' => ['handler' => ['App\Controllers\FavoriteController', 'index'], 'auth' => true],
  'POST /api/favorites' => ['handler' => ['App\Controllers\FavoriteController', 'store'], 'auth' => true],

  'GET /api/notifications' => ['handler' => ['App\Controllers\NotificationController', 'index'], 'auth' => true],
  'POST /api/notifications' => ['handler' => ['App\Controllers\NotificationController', 'store'], 'auth' => true],
  'POST /api/notifications/read' => ['handler' => ['App\Controllers\NotificationController', 'markRead'], 'auth' => true],


  'GET /api/reports/overview' => ['handler' => ['App\Controllers\ReportController', 'overview'], 'auth' => true, 'role' => ['admin']],
  'GET /api/reports/export-csv' => ['handler' => ['App\Controllers\ReportController', 'exportCsv'], 'auth' => true, 'role' => ['admin']],

  'GET /api/admin/metrics' => ['handler' => ['App\\Controllers\\AdminController', 'metrics'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/users/ban' => ['handler' => ['App\\Controllers\\AdminController', 'banUser'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/jobs/approve' => ['handler' => ['App\\Controllers\\AdminController', 'approveJob'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/services/approve' => ['handler' => ['App\\Controllers\\AdminController', 'approveService'], 'auth' => true, 'role' => ['admin']],
  'POST /api/admin/disputes/resolve' => ['handler' => ['App\\Controllers\\DisputeController', 'resolve'], 'auth' => true, 'role' => ['admin']],
];
