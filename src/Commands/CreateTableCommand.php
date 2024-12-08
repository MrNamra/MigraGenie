<?php

namespace MigraGenie\Commands;

use Illuminate\Support\Str;

class CreateTableCommand extends BaseCommand
{
    protected $signature = 'migragenie:create';
    protected $description = 'Create a new table migration with interactive input';

    public function handle()
    {
        $tableName = $this->ask('Enter the table name');
        $idType = $this->askWithOptions('Choose ID type', ['int', 'uuid']);
        $columns = [];
        
        while (true) {
            $field = $this->ask('Enter column name (or type "done" to finish)');
            if ($field === 'done') break;

            $dataType = $this->askWithOptions("Choose data type for $field", ['string', 'integer', 'boolean', 'text', 'date', 'float']);
            $columns[] = compact('field', 'dataType');
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
            return "\$table->{$col['dataType']}('{$col['field']}');";
        })->implode("\n            ");

        $idLine = $idType === 'uuid' ? "\$table->uuid('id')->primary();" : "\$table->increments('id');";

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
