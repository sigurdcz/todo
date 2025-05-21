<?php
namespace App\Dto;

final class DeleteTaskRequestDto
{
    public function __construct(
        public readonly string $hash,
        public readonly int $taskId
    ) {}
}
