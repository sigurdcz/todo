<?php
namespace App\Dto;

final class AddTaskRequestDto
{
    public function __construct(
        public readonly string $hash,
        public readonly string $description
    ) {}
}
