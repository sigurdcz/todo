<?php
namespace App\Controller;

use App\Core\Request;
use App\Service\AuthService;

class AuthController
{
    public function __construct(private AuthService $authService) {}

    public function login(Request $request): void
    {
        $token = $request->getQueryParam('token', '');
        if (!$token) {
            http_response_code(400);
            echo json_encode(['error' => 'Token missing']);
            return;
        }
        $result = $this->authService->login($token);
        echo json_encode($result);
    }
}
