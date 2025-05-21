<?php
namespace App\Controller;

use App\Service\AuthService;

class AuthController {
    public function __construct(private AuthService $auth) {}

    public function login(string $token): void {
        $user = $this->auth->login($token);
        if ($user) {
            echo json_encode(['user' => $user]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
        }
    }
}