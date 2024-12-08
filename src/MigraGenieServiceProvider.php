<?php

namespace Mrnamra\Migragenie;

use Illuminate\Support\ServiceProvider;
use Mrnamra\Migragenie\Commands\CreateTableCommand;
use Mrnamra\Migragenie\Commands\AlterTableCommand;
use Mrnamra\Migragenie\Commands\SetupStructureCommand;

class MigragenieServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            CreateTableCommand::class,
            AlterTableCommand::class,
            SetupStructureCommand::class,
        ]);
    }
}
