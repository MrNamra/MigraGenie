<?php

namespace MigraGenie;

use Illuminate\Support\ServiceProvider;
use MigraGenie\Commands\CreateTableCommand;
use MigraGenie\Commands\AlterTableCommand;
use MigraGenie\Commands\SetupStructureCommand;

class MigraGenieServiceProvider extends ServiceProvider
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
