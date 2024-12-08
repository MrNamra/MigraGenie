<?php

namespace MigraGenie\Commands;

use Illuminate\Support\Facades\Schema;

class AlterTableCommand extends BaseCommand
{
    protected $signature = 'migragenie:alter';
    protected $description = 'Alter an existing table with interactive input';

    public function handle()
    {
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        $tableName = $this->askWithOptions('Select a table to alter', $tables);

        $options = ['Add Column', 'Drop Column', 'Modify Column', 'done'];
        $operations = [];

        while (true) {
            $choice = $this->askWithOptions('Choose an operation', $options);
            if ($choice === 'done') break;

            switch ($choice) {
                case 'Add Column':
                    $field = $this->ask('Enter new column name');
                    $dataType = $this->askWithOptions("Choose data type for $field", ['string', 'integer', 'boolean', 'text', 'date', 'float']);
                    $operations[] = "\$table->{$dataType}('$field');";
                    break;

                case 'Drop Column':
                    $field = $this->ask('Enter column name to drop');
                    $operations[] = "\$table->dropColumn('$field');";
                    break;

                case 'Modify Column':
                    $field = $this->ask('Enter column name to modify');
                    $dataType = $this->askWithOptions("Choose new data type for $field", ['string', 'integer', 'boolean', 'text', 'date', 'float']);
                    $operations[] = "\$table->{$dataType}('$field')->change();";
                    break;
            }
        }

        $fileName = date('Y_m_d_His') . "_alter_{$tableName}_table.php";
        $path = database_path("migrations/$fileName");

        $migrationContent = $this->generateAlterMigration($tableName, $operations);
        file_put_contents($path, $migrationContent);

        $this->info("Migration created: $path");
    }

    protected function generateAlterMigration($tableName, $operations)
    {
        $operationsCode = implode("\n            ", $operations);

        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alter{$tableName}Table extends Migration
{
    public function up()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            $operationsCode
        });
    }

    public function down()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            // Reverse the operations made in `up()`
            foreach ({$operations} as \$operation) {
                \$table->{$operation}->reverse();
            }
        });
    }
}
EOT;
    }
}
