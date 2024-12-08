<?php
namespace Mrnamra\Migragenie\Commands;

use Illuminate\Console\Command;
use Mrnamra\Migragenie\Generators\MigrationGenerator;
use Mrnamra\Migragenie\Services\TableHelper;

class AlterTableCommand extends Command
{
    protected $signature = 'migragenie:alter';
    protected $description = 'Alter an existing table by adding, modifying, or removing columns.';
    
    public function __construct(
        protected TableHelper $tableHelper,
        protected MigrationGenerator $migrationGenerator
        ) {
        parent::__construct();
    }

    public function handle()
    {
        $tables = $this->tableHelper->getAllTableNames();

        if (empty($tables)) {
            $this->error('No tables found in the database.');
            return;
        }

        $tableName = $this->choice('Select a table to alter', $tables);
        $actions = ['add column', 'modify column', 'remove column'];
        $action = $this->choice('Choose action', $actions);

        match ($action) {
            'add column' => $this->migrationGenerator->addColumn($this, $tableName),
            // 'modify column' => $this->migrationGenerator->modifyColumn($this, $tableName),
            // 'remove column' => $this->migrationGenerator->removeColumn($this, $tableName),
            default => $this->error('Invalid action selected.'),
        };
    }
}
