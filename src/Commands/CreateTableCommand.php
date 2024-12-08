<?php

namespace Mrnamra\Migragenie\Commands;

use Mrnamra\Migragenie\Commands\BaseCommand;

class CreateTableCommand extends BaseCommand
{
    protected $signature = 'migragenie:create';
    protected $description = 'Create a new table migration with interactive input';

    public function handle()
    {
        $tableName = $this->ask('Enter the table name');
        $idType = $this->choice('Choose ID type', ['int', 'uuid'], 0);
        $columns = [];
        
        while (true) {
            $field = $this->ask('Enter column name (or type "done" to finish)');
            if (strtolower($field) === 'done') break;

            $dataType = $this->choice("Choose data type for $field", ['string', 'integer', 'boolean', 'text', 'date', 'float']);
            $nullable = $this->choice("Should $field be nullable?", ['NO', 'yes'], 0) === 'yes' ? '->nullable()' : '';
            $columns[] = compact('field', 'dataType', 'nullable');
        }

        // Generate migration file
        $fileName = date('Y_m_d_His') . "_create_{$tableName}_table.php";
        $path = database_path("migrations/$fileName");

        $migrationContent = $this->generateCreateMigration($tableName, $idType, $columns);
        file_put_contents($path, $migrationContent);

        $this->info("Migration created: $path");
    }

    protected function generateCreateMigration($tableName, $idType, $columns)
    {
        $fieldsCode = collect($columns)->map(function ($col) {
            return "\$table->{$col['dataType']}('{$col['field']}'){$col['nullable']};";
        })->implode("\n            ");

        $idLine = $idType === 'uuid' ? "\$table->uuid('id')->primary();" : "\$table->id();";

        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create{$tableName}Table extends Migration
{
    public function up()
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            $idLine
            $fieldsCode
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('$tableName');
    }
}
EOT;
    }
}
