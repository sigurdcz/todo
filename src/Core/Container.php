<?php
namespace App\Core;

class Container
{
    private array $services = [];
    private array $instances = [];

    public function set(string $name, callable $resolver): void
    {
        $this->services[$name] = $resolver;
    }

    public function get(string $name)
    {
        if (!isset($this->instances[$name])) {
            if (!isset($this->services[$name])) {
                throw new \Exception("Service $name not found.");
            }
            $this->instances[$name] = $this->services[$name]($this);
        }

        return $this->instances[$name];
    }
}
