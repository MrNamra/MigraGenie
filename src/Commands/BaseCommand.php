<?php

namespace Mrnamra\Migragenie\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    protected function askWithOptions($question, $options)
    {
        $this->info("Options: " . implode(', ', $options));
        return $this->anticipate($question, $options);
    }
}