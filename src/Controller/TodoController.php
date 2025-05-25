<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\TodoListModel;
use App\Model\TaskModel;
use App\Validator\TodoRequestValidator;
use App\Core\ViewRenderer;
use App\Service\AuthService;

class TodoController
{
    public function __construct(
        private TodoListModel $lists,
        private TaskModel $tasks,
        private ViewRenderer $viewRenderer,
        private AuthService $authService
    ) {}

    // Nová metoda pro zobrazení seznamu todo listů
    public function index(Request $request): void
    {
        // Předpokládejme, že AuthService je dostupný přes DI kontejner a má metodu getUserId()
        $userId = $this->authService->getCurrentUser()['id'];

        if (!$userId) {
            // uživatel není přihlášen, přesměruj nebo error
            http_response_code(401);
            echo "Unauthorized";
            return;
        }

        $lists = $this->lists->getAllByUserId($userId);
        $this->viewRenderer->render('todo/index', ['lists' => $lists]);
    }


    // Nová metoda pro zobrazení detailu konkrétního todo listu a jeho tasků
    public function show(Request $request, string $hash): void
    {
        $list = $this->lists->findByHash($hash);
        if (!$list) {
            http_response_code(404);
            $this->viewRenderer->render('errors/404');
            return;
        }
        $tasks = $this->tasks->getAll($list['id']);
        $this->viewRenderer->render('todo/show', ['list' => $list, 'tasks' => $tasks]);
    }

    // ostatní metody (getList, addTask, updateTask, deleteTask) můžeš nechat, ale getList už nebude třeba, nebo můžeš odstranit

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
}
