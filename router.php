<?php
declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\TodoController;
use App\Core\Request;
use App\Core\Middleware\AuthMiddleware;

/**
 * @param Psr\Container\ContainerInterface $container
 */
function routeRequest($container): void
{
    $request = new Request();
    $uri = $request->getUri();
    $method = $request->getMethod();

    $contentTypeHeader = $request->getContentTypeHeader();
    if ($contentTypeHeader) {
        header($contentTypeHeader);
    }

    $protectedRoutes = [
        '#^/todo#',
    ];

    foreach ($protectedRoutes as $pattern) {
        if (preg_match($pattern, $uri)) {
            $middleware = new AuthMiddleware($container->get(\App\Service\AuthService::class));
            $middleware->handle($request);
            break;
        }
    }

    switch (true) {
        case $uri === '/auth/login' && $method === 'GET':
            $container->get(AuthController::class)->login($request);
            break;

        case preg_match('#^/todo/list/([a-zA-Z0-9]+)$#', $uri, $m) && $method === 'GET':
            $container->get(TodoController::class)->getList($request);
            break;

        case preg_match('#^/todo/list/([a-zA-Z0-9]+)/task$#', $uri, $m) && $method === 'POST':
            $container->get(TodoController::class)->addTask($request);
            break;

        case preg_match('#^/todo/list/([a-zA-Z0-9]+)/task/([0-9]+)$#', $uri, $m) && $method === 'PUT':
            $container->get(TodoController::class)->updateTask($request);
            break;

        case preg_match('#^/todo/list/([a-zA-Z0-9]+)/task/([0-9]+)$#', $uri, $m) && $method === 'DELETE':
            $container->get(TodoController::class)->deleteTask($request);
            break;

        case $uri === '/todo' && $method === 'GET':
            $container->get(TodoController::class)->index($request);
            break;

        case $uri === '/auth/login' && $method === 'POST':
            $container->get(AuthController::class)->handleLogin($request);
            break;

        case $uri === '/auth/register' && $method === 'GET':
            $container->get(AuthController::class)->registerForm();
            break;

        case $uri === '/auth/register' && $method === 'POST':
            $container->get(AuthController::class)->handleRegister($request);
            break;

        case $uri === '/auth/logout' && $method === 'GET':
            $container->get(AuthController::class)->logout($request);
            break;
            
        case $uri === '/migrate' && $method === 'GET':
            $container->get(App\Controller\MigrationController::class)->run($request);
            break;

        default:
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['error' => 'Not found', 'type' => '404']);
            break;
    }
}
