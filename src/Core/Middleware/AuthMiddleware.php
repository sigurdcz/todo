<?php
namespace App\Core\Middleware;

use App\Service\AuthService;
use App\Core\Request;

class AuthMiddleware
{
    public function __construct(private AuthService $authService) {}

    public function handle(Request $request): void
    {
        if (!$this->authService->isAuthenticated()) {
            header('Location: /auth/login');
            exit;
        }
    }
}
