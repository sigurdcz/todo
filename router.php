<?php
declare(strict_types=1);

use App\Controller\AuthController;
use App\Controller\TodoController;
use App\Core\Request;

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

        default:
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['error' => 'Not found', 'type' => '404']);
            break;
    }
}
