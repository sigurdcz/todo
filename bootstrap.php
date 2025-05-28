<?php

declare(strict_types=1);

require_once __DIR__ . '/autoload.php';

use App\Core\Container;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Request;
use App\Core\ViewRenderer;

use App\Model\UserModel;
use App\Model\TodoListModel;
use App\Model\TaskModel;
use App\Model\UserTodoListModel;
use App\Model\MigrationModel;

use App\Service\AuthService;

use App\Controller\AuthController;
use App\Controller\TodoController;
use App\Controller\MigrationController;

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load environment config
$env = require __DIR__ . '/config/env.php';

// Create DI container
$container = new Container();

// === Core services ===
$container->set('env', fn() => $env);

$container->set(Database::class, fn() => new Database($env));
$container->set('db', fn($c) => $c->get(Database::class)->getConnection());

$container->set(Request::class, fn() => new Request());
$container->set(ViewRenderer::class, fn() => new ViewRenderer());
$container->set(Logger::class, fn($c) => new Logger($c->get('env')));

// === Models ===
$container->set(UserModel::class, fn($c) => new UserModel($c->get('db')));
$container->set(TodoListModel::class, fn($c) => new TodoListModel($c->get('db')));
$container->set(TaskModel::class, fn($c) => new TaskModel($c->get('db')));
$container->set(UserTodoListModel::class, fn($c) => new UserTodoListModel($c->get('db')));
$container->set(MigrationModel::class, fn($c) => new MigrationModel($c->get('db')));

// === Services ===
$container->set(AuthService::class, fn($c) => new AuthService(
    $c->get(UserModel::class)
));

// === Controllers ===
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

$container->set(MigrationController::class, fn() => new MigrationController(
    $container->get(MigrationModel::class),
    $container->get(ViewRenderer::class),
    $container->get('env') 
));

return $container;
