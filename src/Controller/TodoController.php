<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\TodoListModel;
use App\Model\TaskModel;
use App\Validator\TodoRequestValidator;

class TodoController
{
    public function __construct(
        private TodoListModel $lists,
        private TaskModel $tasks
    ) {}

    public function getList(Request $request): void
    {
        $dto = TodoRequestValidator::validateGetList($request);
        if (!$dto) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid URI']);
            return;
        }

        $list = $this->lists->findByHash($dto->hash);
        if (!$list) {
            http_response_code(404);
            echo json_encode(['error' => 'List not found']);
            return;
        }

        $tasks = $this->tasks->getAll($list['id']);
        echo json_encode(['list' => $list, 'tasks' => $tasks]);
    }

    public function addTask(Request $request): void
    {
        $dto = TodoRequestValidator::validateAddTask($request);
        if (!$dto) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid URI or data']);
            return;
        }

        $list = $this->lists->findByHash($dto->hash);
        if (!$list) {
            http_response_code(404);
            echo json_encode(['error' => 'List not found']);
            return;
        }

        $this->tasks->create($list['id'], $dto->description);
        echo json_encode(['status' => 'ok']);
    }

    public function updateTask(Request $request): void
    {
        $dto = TodoRequestValidator::validateUpdateTask($request);
        if (!$dto) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid URI or data']);
            return;
        }

        $list = $this->lists->findByHash($dto->hash);
        if (!$list) {
            http_response_code(404);
            echo json_encode(['error' => 'List not found']);
            return;
        }
 
        $this->tasks->update($dto->taskId, $dto->description, $dto->completed);
        echo json_encode(['status' => 'updated']);
    }

    public function deleteTask(Request $request): void
    {
        $dto = TodoRequestValidator::validateDeleteTask($request);
        if (!$dto) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid URI']);
            return;
        }

        $list = $this->lists->findByHash($dto->hash);
        if (!$list) {
            http_response_code(404);
            echo json_encode(['error' => 'List not found']);
            return;
        }

        $this->tasks->delete($dto->taskId);
        echo json_encode(['status' => 'deleted']);
    }

    public function index(Request $request): void
    {
        include __DIR__ . '/../../views/todo/index.phtml';
    }
}
