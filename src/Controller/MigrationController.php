<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\MigrationModel;
use App\Core\ViewRenderer;

class MigrationController
{
    public function __construct(
        private MigrationModel $migrationModel,
        private ViewRenderer $viewRenderer
    ) {}

    public function run(Request $request): void
    {
        $migrationsDir = $this->env['MIGRATIONS_DIR'] ?? __DIR__ . '/../../migrations';
        $output = $this->migrationModel->runMigrations($migrationsDir);
        $this->viewRenderer->render('migrations/result', ['output' => $output]);
    }
}
