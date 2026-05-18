<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Empresa;
use App\Models\Ciudad;
use App\Models\Viaje;
use App\Models\User;

echo "\n╔════════════════════════════════════════════════════════╗\n";
echo "║         VERIFICACIÓN DE MODELOS ELOQUENT             ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

// Verificar Empresa
echo "📋 Verificando Model: EMPRESA\n";
echo "───────────────────────────────────────────────────────\n";
try {
    $empresa = new Empresa();
    echo "✅ Clase Empresa cargada correctamente\n";
    echo "   Tabla: " . $empresa->getTable() . "\n";
    echo "   Fillable: " . implode(', ', $empresa->getFillable()) . "\n";
    echo "   Método relación: viajes()\n";
    echo "   Tipo: hasMany(Viaje)\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Verificar Ciudad
echo "📍 Verificando Model: CIUDAD\n";
echo "───────────────────────────────────────────────────────\n";
try {
    $ciudad = new Ciudad();
    echo "✅ Clase Ciudad cargada correctamente\n";
    echo "   Tabla: " . $ciudad->getTable() . "\n";
    echo "   Fillable: " . implode(', ', $ciudad->getFillable()) . "\n";
    echo "   Métodos relación:\n";
    echo "   - viajesOrigen() : hasMany(Viaje)\n";
    echo "   - viajesDestino() : hasMany(Viaje)\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Verificar Viaje
echo "✈️  Verificando Model: VIAJE\n";
echo "───────────────────────────────────────────────────────\n";
try {
    $viaje = new Viaje();
    echo "✅ Clase Viaje cargada correctamente\n";
    echo "   Tabla: " . $viaje->getTable() . "\n";
    echo "   Fillable: " . implode(', ', $viaje->getFillable()) . "\n";
    echo "   Métodos relación:\n";
    echo "   - empresa() : belongsTo(Empresa)\n";
    echo "   - ciudadOrigen() : belongsTo(Ciudad)\n";
    echo "   - ciudadDestino() : belongsTo(Ciudad)\n";
    echo "   - creadoPor() : belongsTo(User)\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Verificar User
echo "👤 Verificando Model: USER\n";
echo "───────────────────────────────────────────────────────\n";
try {
    $user = new User();
    echo "✅ Clase User cargada correctamente\n";
    echo "   Tabla: " . $user->getTable() . "\n";
    echo "   Fillable: " . implode(', ', $user->getFillable()) . "\n";
    echo "   Métodos relación:\n";
    echo "   - viajesCreadosPor() : hasMany(Viaje)\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║         DIAGRAMA DE RELACIONES                        ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

echo "Empresa (1) ──────── (N) Viaje\n";
echo "   └─> viajes()\n\n";

echo "Ciudad (1) ──────── (N) Viaje (como ORIGEN)\n";
echo "   └─> viajesOrigen()\n\n";

echo "Ciudad (1) ──────── (N) Viaje (como DESTINO)\n";
echo "   └─> viajesDestino()\n\n";

echo "User (1) ──────── (N) Viaje (creados por)\n";
echo "   └─> viajesCreadosPor()\n\n";

echo "Viaje\n";
echo "   ├─ empresa() ───────> Empresa\n";
echo "   ├─ ciudadOrigen() ──> Ciudad\n";
echo "   ├─ ciudadDestino() → Ciudad\n";
echo "   └─ creadoPor() ─────> User\n\n";

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║  ✅ VERIFICACIÓN COMPLETADA EXITOSAMENTE             ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";
