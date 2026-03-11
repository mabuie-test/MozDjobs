<?php
return [
  'GET /api/health' => fn() => ['status' => 'ok'],
  'POST /api/auth/register' => ['App\\Controllers\\AuthController','register'],
  'POST /api/auth/login' => ['App\\Controllers\\AuthController','login'],
  'GET /api/jobs' => ['App\\Controllers\\JobController','index'],
  'POST /api/jobs' => ['App\\Controllers\\JobController','store'],
  'GET /api/services' => ['App\\Controllers\\ServiceController','index'],
  'POST /api/services' => ['App\\Controllers\\ServiceController','store'],
  'GET /api/chat' => ['App\\Controllers\\ChatController','index'],
  'POST /api/chat' => ['App\\Controllers\\ChatController','store'],
];
