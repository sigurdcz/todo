<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\MigrationModel;

class MigrationController
{
    public function __construct(
        private MigrationModel $migrationModel
    ) {}

    public function run(Request $request): void
    {
        $migrationsDir = __DIR__ . '/../../migrations';
        $result = $this->migrationModel->runMigrations($migrationsDir);

        require __DIR__ . '/../../views/migrations/result.phtml';
    }
}
