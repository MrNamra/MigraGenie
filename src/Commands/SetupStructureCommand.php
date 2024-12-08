<?php

namespace YourVendor\YourPackage\Commands;

use Illuminate\Console\Command;

class SetupStructureCommand extends Command
{
    protected $signature = 'migragenie:setup';
    protected $description = 'Setup initial structure and configuration for MigraGenie package';

    public function handle()
    {
        $this->info('Setting up MigraGenie...');

        // Step 1: Create a `migragenie` directory in `database/migrations`
        $directory = database_path('migragenie');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            $this->info("Created directory: $directory");
        } else {
            $this->info("Directory already exists: $directory");
        }

        // Step 2: Publish a configuration file (if needed)
        $configPath = config_path('migragenie.php');
        if (!file_exists($configPath)) {
            $configTemplate = <<<EOT
            <?php

            return [
                // Add default configurations for MigraGenie
                'default_id_type' => 'int', // Options: 'int', 'uuid'
                'migration_path' => 'database/migragenie', // Custom migration storage path
            ];
            EOT;
            file_put_contents($configPath, $configTemplate);
            $this->info("Published configuration file: $configPath");
        } else {
            $this->info("Configuration file already exists: $configPath");
        }

        // Additional setup tasks can be added here
        $this->info('MigraGenie setup completed!');
    }
}
