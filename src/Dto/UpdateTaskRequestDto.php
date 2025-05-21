<?php
namespace App\Dto;

final class UpdateTaskRequestDto
{
    public function __construct(
        public readonly string $hash,
        public readonly int $taskId,
        public readonly string $description,
        public readonly bool $completed = false
    ) {}
}
