<?php
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/autoload.php';

use App\Core\Container;
use App\Service\AuthService;
use App\Model\UserModel;
use App\Model\TodoListModel;
use App\Model\TaskModel;
use App\Controller\AuthController;
use App\Controller\TodoController;

$container = new Container();
$container->set('db', fn() => require __DIR__ . '/config/db.php');

$container->set(UserModel::class, fn($c) => new UserModel($c->get('db')));
$container->set(TodoListModel::class, fn($c) => new TodoListModel($c->get('db')));
$container->set(TaskModel::class, fn($c) => new TaskModel($c->get('db')));
$container->set(AuthService::class, fn($c) => new AuthService($c->get(UserModel::class)));
$container->set(AuthController::class, fn($c) => new AuthController($c->get(AuthService::class)));
$container->set(TodoController::class, fn($c) => new TodoController($c->get(TodoListModel::class), $c->get(TaskModel::class)));

return $container;
