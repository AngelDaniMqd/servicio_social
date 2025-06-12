<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $columns = DB::select("DESCRIBE alumno");
    echo "Columnas de la tabla alumno:\n";
    echo "============================\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n\nPrimeros registros de ejemplo:\n";
    echo "==============================\n";
    $sample = DB::table('alumno')->limit(1)->get();
    if ($sample->isNotEmpty()) {
        $first = $sample->first();
        foreach ((array)$first as $key => $value) {
            echo "- {$key}: " . (is_null($value) ? 'NULL' : $value) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}