<?php
namespace App\Dto;

final class TodoListRequestDto
{
    public function __construct(
        public readonly string $hash
    ) {}
}
