<?php
namespace App\Core;

class Request
{
    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getUri(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?') ?: '/';
    }

    public function getQueryParam(string $name, $default = null)
    {
        return $_GET[$name] ?? $default;
    }

    public function getJsonBody(): ?array
    {
        $input = file_get_contents('php://input');
        if (!$input) {
            return null;
        }
        $data = json_decode($input, true);
        return is_array($data) ? $data : null;
    }

    public function getPostBody(): ?array
    {
        return $_POST ?: null;
    }

    public function getContentTypeHeader(): ?string
    {
        $uri = $this->getUri();
        $method = $this->getMethod();

        if (
            preg_match('#^/auth/login#', $uri) ||
            preg_match('#^/todo/list#', $uri)
        ) {
            return 'Content-Type: application/json';
        }

        return null;
    }

    /**
     * Provede redirect na zadanou URL.
     *
     * @param string $url Kam redirectovat (např. /auth/login)
     * @param array $headers Volitelné další hlavičky k odeslání
     * @param bool $preserveQueryParams Pokud true, připojí původní query string nebo hash
     */
    public function redirect(string $url, array $headers = []): void
    {
        // Odeslat další hlavičky
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        // Základní Location hlavička
        header("Location: $url");
        exit;
    }

}
