<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n========================================\n";
echo "VERIFICACIÓN DE TABLAS CREADAS\n";
echo "========================================\n\n";

// Tabla Empresas
echo "📋 TABLA: EMPRESAS\n";
echo "─────────────────────────────────────\n";
if (Schema::hasTable('empresas')) {
    $columns = DB::select("DESCRIBE empresas");
    foreach ($columns as $col) {
        printf("%-20s | %-30s | %s\n", $col->Field, $col->Type, ($col->Null === 'YES' ? 'NULL' : 'NOT NULL'));
    }
    echo "✅ Tabla creada correctamente\n\n";
} else {
    echo "❌ Tabla no encontrada\n\n";
}

// Tabla Ciudades
echo "📍 TABLA: CIUDADES\n";
echo "─────────────────────────────────────\n";
if (Schema::hasTable('ciudades')) {
    $columns = DB::select("DESCRIBE ciudades");
    foreach ($columns as $col) {
        printf("%-25s | %-30s | %s\n", $col->Field, $col->Type, ($col->Null === 'YES' ? 'NULL' : 'NOT NULL'));
    }
    echo "✅ Tabla creada correctamente\n\n";
} else {
    echo "❌ Tabla no encontrada\n\n";
}

// Tabla Viajes
echo "✈️  TABLA: VIAJES\n";
echo "─────────────────────────────────────\n";
if (Schema::hasTable('viajes')) {
    $columns = DB::select("DESCRIBE viajes");
    foreach ($columns as $col) {
        printf("%-25s | %-30s | %s\n", $col->Field, $col->Type, ($col->Null === 'YES' ? 'NULL' : 'NOT NULL'));
    }
    echo "✅ Tabla creada correctamente\n\n";
} else {
    echo "❌ Tabla no encontrada\n\n";
}

// Tabla Users
echo "👤 TABLA: USERS (Modificada)\n";
echo "─────────────────────────────────────\n";
if (Schema::hasTable('users')) {
    $columns = DB::select("DESCRIBE users");
    foreach ($columns as $col) {
        printf("%-25s | %-30s | %s\n", $col->Field, $col->Type, ($col->Null === 'YES' ? 'NULL' : 'NOT NULL'));
    }
    echo "✅ Tabla modificada correctamente\n\n";
} else {
    echo "❌ Tabla no encontrada\n\n";
}

// Foreign Keys
echo "\n🔗 RELACIONES (FOREIGN KEYS)\n";
echo "─────────────────────────────────────\n";
$constraints = DB::select("
    SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'laravel' AND REFERENCED_TABLE_NAME IS NOT NULL
    ORDER BY TABLE_NAME
");

foreach ($constraints as $fk) {
    printf("%-20s: %s.%s → %s.%s\n", 
        $fk->CONSTRAINT_NAME,
        $fk->TABLE_NAME,
        $fk->COLUMN_NAME,
        $fk->REFERENCED_TABLE_NAME,
        $fk->REFERENCED_COLUMN_NAME
    );
}

echo "\n✅ VERIFICACIÓN COMPLETADA\n";
echo "========================================\n\n";
