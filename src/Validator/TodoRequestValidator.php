<?php
namespace App\Validator;

use App\Core\Request;
use App\Dto\TodoListRequestDto;
use App\Dto\AddTaskRequestDto;
use App\Dto\UpdateTaskRequestDto;
use App\Dto\DeleteTaskRequestDto;

class TodoRequestValidator
{
    public static function validateGetList(Request $request): ?TodoListRequestDto
    {
        $uri = $request->getUri();
        if (!preg_match('#^/todo/list/([a-zA-Z0-9]+)$#', $uri, $m)) {
            return null;
        }
        return new TodoListRequestDto($m[1]);
    }

    public static function validateAddTask(Request $request): AddTaskRequestDto
    {
        $uri = $request->getUri();
        if (!preg_match('#^/todo/list/([a-zA-Z0-9]+)/task$#', $uri, $m)) {
            throw new \InvalidArgumentException('Invalid URI');
        }
        $hash = $m[1];

        $data = $request->getJsonBody();
        if (!$data || empty($data['description']) || !is_string($data['description'])) {
            throw new \InvalidArgumentException('Invalid data: description missing or not a string');
        }

        return new AddTaskRequestDto($hash, $data['description']);
    }

    public static function validateUpdateTask(Request $request): ?UpdateTaskRequestDto
    {
        $uri = $request->getUri();
        if (!preg_match('#^/todo/list/([a-zA-Z0-9]+)/task/([0-9]+)$#', $uri, $m)) {
            return null;
        }

        $data = $request->getJsonBody();
        if (!$data) {
            return null;
        }

        $data = $request->getJsonBody();
        if (!$data || empty($data['description']) || !is_string($data['description'])) {
            throw new \InvalidArgumentException('Invalid data: description missing or not a string');
        }

        if (!$data || !isset($data['completed'])) {
            throw new \InvalidArgumentException('Invalid data: completed missing or not a bool ('.$data['completed'].') ');
        }
        $data['completed'] = (bool) ($data['completed'] === 0 ? false : true);
 
        return new UpdateTaskRequestDto($m[1], (int)$m[2], $data['description'], (bool) $data['completed']);
    }

    public static function validateDeleteTask(Request $request): ?DeleteTaskRequestDto
    {
        $uri = $request->getUri();
        if (!preg_match('#^/todo/list/([a-zA-Z0-9]+)/task/([0-9]+)$#', $uri, $m)) {
            return null;
        }

        return new DeleteTaskRequestDto($m[1], (int)$m[2]);
    }
}
