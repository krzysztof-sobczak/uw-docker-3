<?php

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__.'/../../vendor/autoload.php';

$request = Request::createFromGlobals();
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', function() {});
    $r->addRoute('GET', '/user/{id:[a-z]+}', function(Request $request, $id) {
        $hitCount = getRedis()->incr("user/hit/$id");
        $userData = getRedis()->get("user/$id") ?: null;
        if (null !== $userData) {
            $userData = json_decode($userData, true);
        }

        return new JsonResponse([
            'id' => $id,
            'data' => $userData,
            'hits' => $hitCount,
        ]);
    });
    $r->addRoute('PUT', '/user/{id:[a-z]+}', function(Request $request, $id) {
        $userData = [
            'name' => $request->get('name'),
        ];
        $userPassword = password_hash($request->get('password'), PASSWORD_DEFAULT);
        getRedis()->set("user/$id", json_encode($userData));
        getRedis()->set("user/password/$id", $userPassword);

        return new JsonResponse([
            'id' => $id,
        ]);
    });
    $r->addRoute('POST', '/auth/login', function(Request $request) {
        $id = $request->get('username');
        $password = $request->get('password');
        $userPassword = getRedis()->get("user/password/$id");
        if (!is_string($userPassword)) {
            return new JsonResponse(false);
        }
        $passwordValid = password_verify($password, $userPassword);
        return new JsonResponse($passwordValid);
    });
});

function getRedis() {
    static $redis;
    if (null === $redis) {
        $redis = new Redis();
        $redis->connect('redis');
    }

    return $redis;
}

//

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $vars = (array)$vars;
        array_unshift($vars, $request);
        /** @var Response $response */
        $response = call_user_func_array($handler, $vars);
        echo $response->getContent();
        break;
}