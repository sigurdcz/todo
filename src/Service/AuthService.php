<?php
namespace App\Service;

use App\Model\UserModel;

class AuthService
{
    public function __construct(private UserModel $userModel)
    {
    }

    /**
     * Přihlášení pomocí tokenu (např. z URL)
     * @param string $token
     * @return array|null Vrací uživatele nebo null
     */
    public function login(string $token): ?array
    {
        // Příklad: najdi uživatele podle tokenu (token třeba uložen v db)
        $user = $this->userModel->findByToken($token);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            return $user;
        }
        return null;
    }

    /**
     * Přihlášení pomocí e-mailu a hesla
     * @param string $email
     * @param string $password
     * @return array|null Vrací uživatele nebo null
     */
    public function loginWithCredentials(string $email, string $password): ?array
    {
        $user = $this->userModel->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return $user;
        }
        return null;
    }

    /**
     * Zjištění, zda je uživatel přihlášen
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Odhlášení uživatele
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['user_id']);
        session_destroy();
    }

    /**
     * Získání dat přihlášeného uživatele
     * @return array|null
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }
        return $this->userModel->findById($_SESSION['user_id']);
    }

    public function userExists(string $email): bool
    {
        return $this->userModel->findByEmail($email) !== null;
    }

    public function registerUser(string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->userModel->createUser($email, $hashedPassword);
    }
}
