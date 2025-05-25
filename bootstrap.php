<?php
require_once __DIR__ . '/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Core\Container;
use App\Service\AuthService;
use App\Model\UserModel;
use App\Model\TodoListModel;
use App\Model\TaskModel;
use App\Model\UserTodoListModel; 
use App\Controller\AuthController;
use App\Controller\TodoController;
use App\Core\Logger;
use App\Core\Request;
use App\Model\MigrationModel;
use App\Controller\MigrationController;
use App\Core\ViewRenderer;

$container = new Container();
$env = require __DIR__ . '/config/env.php';
$container->set('db', fn() => require __DIR__ . '/config/db.php');
$container->set(Request::class, fn($c) => new Request());
$container->set(ViewRenderer::class, function() {
    return new ViewRenderer();
});
$container->set(UserModel::class, fn($c) => new UserModel($c->get('db')));
$container->set(TodoListModel::class, fn($c) => new TodoListModel($c->get('db')));
$container->set(TaskModel::class, fn($c) => new TaskModel($c->get('db')));
$container->set(UserTodoListModel::class, fn($c) => new UserTodoListModel($c->get('db')));  // nový model
$container->set(AuthService::class, fn($c) => new AuthService($c->get(UserModel::class)));
$container->set(AuthController::class, fn($c) => new AuthController(
    $c->get(AuthService::class),
    $c->get(UserTodoListModel::class),
    $c->get(TodoListModel::class),
    $c->get(UserModel::class),
    $c->get(Request::class),
    $c->get(ViewRenderer::class)
));
$container->set(TodoController::class, fn($c) => new TodoController(
    $c->get(TodoListModel::class), 
    $c->get(TaskModel::class),
    $c->get(ViewRenderer::class),
    $c->get(AuthService::class)
));
$container->set(MigrationModel::class, function() use ($container) {
    return new MigrationModel($container->get('db'));
});
$container->set(MigrationController::class, function() use ($container) {
    return new MigrationController(
        $container->get(MigrationModel::class),
        $container->get(ViewRenderer::class)
    );
});
$container->set(Logger::class, function () use ($env) {
    return new Logger($env);
});

return $container;
