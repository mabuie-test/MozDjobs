<?php
spl_autoload_register(function ($class) {
  $path = __DIR__.'/../'.str_replace('App\\', 'app/', str_replace('\\', '/', $class)).'.php';
  if (file_exists($path)) require $path;
});

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$key = "$method $uri";
$routes = array_merge(require __DIR__.'/../routes/web.php', require __DIR__.'/../routes/api.php');
if (!isset($routes[$key])) { http_response_code(404); echo json_encode(['error'=>'not found']); exit; }
$handler = $routes[$key];
$payload = json_decode(file_get_contents('php://input'), true) ?: [];
if (is_callable($handler)) { echo json_encode($handler()); exit; }
[$class, $action] = $handler;
$controller = new $class();
echo json_encode($controller->$action($payload));
