<?php

namespace Mrnamra\Migragenie\Generators;

class MigrationGenerator
{
    public static function generateModifyColumnMigration($tableName, $columnToModify, $newDataType, $nullable)
    {
        $nullableCode = $nullable ? '->nullable()' : '';

        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Modify{$columnToModify}In{$tableName}Table extends Migration
{
    public function up()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            \$table->{$newDataType}('$columnToModify'){$nullableCode}->change();
        });
    }

    public function down()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            // Revert to string type as default (or adjust as per your requirements)
            \$table->string('$columnToModify')->nullable()->change();
        });
    }
}
EOT;
    }

    public static function generateRemoveColumnMigration($tableName, $columnToRemove)
    {
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Remove{$columnToRemove}From{$tableName}Table extends Migration
{
    public function up()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            \$table->dropColumn('$columnToRemove');
        });
    }

    public function down()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            \$table->string('$columnToRemove')->nullable(); // Adjust default type as needed
        });
    }
}
EOT;
    }

    public static function addColumn($data, $tableName)
    {
        print_r($data,$tableName);
    }
}
