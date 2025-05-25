<?php
namespace App\Controller;

use App\Service\AuthService;
use App\Core\Request;
use App\Model\UserTodoListModel;
use App\Model\TodoListModel;
use App\Model\UserModel;
use App\Core\ViewRenderer;

class AuthController
{
    public function __construct(
        private AuthService $auth,
        private UserTodoListModel $userTodoListModel,
        private TodoListModel $todoListModel,
        private UserModel $userModel,
        private Request $request,
        private ViewRenderer $viewRenderer
    ) {}

    public function handleLogin(Request $request): void
    {
        $post = $request->getPostBody();

        $email = trim($post['email'] ?? '');
        $password = trim($post['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->redirectWithError('Email a heslo jsou povinné.');
            return;
        }

        $user = $this->auth->loginWithCredentials($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];

            $firstListId = $this->userTodoListModel->getFirstTodoListHashForUser($user['id']);
  
            if ($firstListId !== null) {
                $this->request->redirect('/todo/' . $firstListId);
            } else {
                $this->request->redirect('/todo'); // fallback
            }
        } else {
            $this->redirectWithError('Neplatné přihlašovací údaje.');
        }
    }
    public function login(Request $request): void
    {
        header('Content-Type: text/html; charset=utf-8');
        $this->viewRenderer->render('/auth/login');
    }

    private function redirectWithError(Request $request, string $message): void
    {
        $_SESSION['error'] = $message;
        $request->redirect('/auth/login', ['Content-Type'=>'text/html']);
    }

    public function registerForm(): void
    {
        header('Content-Type: text/html; charset=utf-8');
        $this->viewRenderer->render('/auth/register');
    }

    public function handleRegister(Request $request): void
    {
        $post = $request->getPostBody();

        $email = trim($post['email'] ?? '');
        $password = trim($post['password'] ?? '');
        $passwordConfirm = trim($post['password_confirm'] ?? '');

        if ($email === '' || $password === '' || $passwordConfirm === '') {
            $this->redirectWithError($request, 'Všechna pole jsou povinná.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithError($request, 'Neplatný formát emailu.');
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->redirectWithError($request, 'Hesla se neshodují.');
            return;
        }

        if ($this->auth->userExists($email)) {
            $this->redirectWithError($request, 'Uživatel s tímto emailem již existuje.');
            return;
        }

        // Zaregistruj uživatele, 
        $this->auth->registerUser($email, $password);

        // vrátí ID nového uživatele nebo false
        $userId = $this->userModel->findByEmail($email)['id'];
 
        if ($userId) {
            // Vytvoř nový todo list s unikátním hashem (např. UUID nebo nějaký náhodný string)
            $hash = bin2hex(random_bytes(16)); // 32 znaků hex
            $todoListId = $this->todoListModel->createTodoList($userId, $hash);
            // Přidej záznam do pivotní tabulky, uživatel je vlastník
            $this->userTodoListModel->addUserToTodoList($userId, $todoListId, true);

            $request->redirect('/auth/login', ['Content-Type' => 'text/html']);
        } else {
            $this->redirectWithError($request, 'Registrace selhala. Zkuste to prosím znovu.');
        }
    }

    public function logout(Request $request): void
    {
        unset($_SESSION['user_id']);
        session_destroy();
        $request->redirect('/auth/login', ['Content-Type'=>'text/html']);
    }
}
