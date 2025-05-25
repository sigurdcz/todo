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
        // Definuj cestu k migracím
        $migrationsDir = __DIR__ . '/../../migrations';

        // Předat cestu do modelu
        $output = $this->migrationModel->runMigrations($migrationsDir);

        // Render výstupu ve view
        $this->viewRenderer->render('migrations/result', ['output' => $output]);
    }
}
