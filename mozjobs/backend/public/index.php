<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;

spl_autoload_register(function ($class) {
  $path = __DIR__.'/../'.str_replace('App\\', 'app/', str_replace('\\', '/', $class)).'.php';
  if (file_exists($path)) {
    require $path;
  }
});

$allowedOrigin = getenv('APP_ALLOWED_ORIGIN') ?: '*';
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'; base-uri 'none';");
header('X-Request-Id: '.bin2hex(random_bytes(8)));
header('Access-Control-Allow-Origin: '.$allowedOrigin);
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

$method = $_SERVER['REQUEST_METHOD'];

$maxBodyBytes = (int) (getenv('MAX_BODY_BYTES') ?: 1048576);
$contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > $maxBodyBytes) {
  http_response_code(413);
  echo json_encode(['error' => 'payload too large']);
  exit;
}
if ($method === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$uri = strtok($_SERVER['REQUEST_URI'], '?');
$key = "$method $uri";
$routes = array_merge(require __DIR__.'/../routes/web.php', require __DIR__.'/../routes/api.php');

if (!(new RateLimitMiddleware())->handle($_SERVER['REMOTE_ADDR'] ?? 'cli')) {
  http_response_code(429);
  echo json_encode(['error' => 'too many requests']);
  exit;
}

$routeConfig = $routes[$key] ?? null;
if (!$routeConfig) {
  http_response_code(404);
  echo json_encode(['error' => 'not found']);
  exit;
}

if (is_callable($routeConfig)) {
  echo json_encode($routeConfig());
  exit;
}

$handler = $routeConfig['handler'] ?? $routeConfig;
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
if ($method === 'GET') {
  $payload = array_merge($payload, $_GET);
}

$currentUser = null;
if (($routeConfig['auth'] ?? false) === true) {
  $currentUser = (new AuthMiddleware())->userFromRequest();
  if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
  }

  $roles = $routeConfig['role'] ?? [];
  if ($roles && !in_array($currentUser['role'] ?? '', $roles, true)) {
    http_response_code(403);
    echo json_encode(['error' => 'forbidden']);
    exit;
  }
}

if (is_callable($handler)) {
  echo json_encode($handler());
  exit;
}

[$class, $action] = $handler;
$controller = new $class();
$response = $controller->$action(array_merge($payload, ['auth_user' => $currentUser]));
$status = isset($response['error']) ? 422 : 200;
http_response_code($status);
echo json_encode($response);
