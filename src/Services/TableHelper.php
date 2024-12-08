<?php
namespace Mrnamra\Migragenie\Services;

use Illuminate\Support\Facades\DB;

class TableHelper
{
    public function getAllTableNames(): array
    {
        try {
            $tables = DB::select('SHOW TABLES');
            return array_map(fn($table) => reset((array)$table), $tables);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTableColumns(string $tableName): array
    {
        try {
            $database = env('DB_DATABASE');
            $columns = DB::select("
                SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
            ", [$database, $tableName]);

            return array_map(fn($column) => $column->COLUMN_NAME, $columns);
        } catch (\Exception $e) {
            return [];
        }
    }
}
