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

    /**
     * Vrací data z JSON těla requestu jako asociativní pole, nebo null.
     */
    public function getJsonBody(): ?array
    {
        $input = file_get_contents('php://input');
        if (!$input) {
            return null;
        }
        $data = json_decode($input, true);
        return is_array($data) ? $data : null;
    }

    public function getContentTypeHeader(): ?string
    {
        $uri = $this->getUri();
        $method = $this->getMethod();

        // API odpovědi mají JSON header
        if (
            preg_match('#^/auth/login#', $uri) ||
            preg_match('#^/todo/list#', $uri)
        ) {
            return 'Content-Type: application/json';
        }

        // HTML stránka nemá Content-Type JSON, necháváme null
        if ($uri === '/todo' && $method === 'GET') {
            return null;
        }

        return null;
    }
}
