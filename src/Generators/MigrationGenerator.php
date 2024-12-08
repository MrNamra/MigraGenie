<?php
namespace Mrnamra\Migragenie\Generators;

class MigrationGenerator
{
    protected $signature = 'migragenie:generate';

    public function generateAlterMigration($tableName, $field, $dataType, $nullable)
    {
        $nullableCode = $nullable ? '->nullable()' : '';

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
            \$table->{$dataType}('$field'){$nullableCode};
        });
    }

    public function down()
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            \$table->dropColumn('$field');
        });
    }
}
EOT;
    }

    public function generateModifyColumnMigration($tableName, $columnToModify, $newDataType, $nullable)
    {
        $nullableCode = $nullable ? '->nullable()' : '';

        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Modify{$columnToModify}InTable extends Migration
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
            \$table->string('$columnToModify')->nullable()->change();
        });
    }
}
EOT;
    }

    public function generateRemoveColumnMigration($tableName, $columnToRemove)
    {
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Remove{$columnToRemove}FromTable extends Migration
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
            \$table->string('$columnToRemove')->nullable();
        });
    }
}
EOT;
    }
}
