<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\CiudadController;
use App\Http\Controllers\Api\ViajeController;

/**
 * API Routes
 * Prefijo: /api/v1
 * Todas las rutas requieren el prefijo /api/v1
 */

Route::prefix('v1')->group(function () {

    /**
     * RUTAS DE AUTENTICACIÓN (públicas)
     */
    Route::prefix('auth')->group(function () {
        // El registro es solo interno — no hay endpoint público para crear usuarios
        Route::post('/login',    [AuthController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me',      [AuthController::class, 'me'])->name('auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        });
    });

    /**
     * RUTAS PÚBLICAS (sin autenticación)
     */

    // Viajes - Consulta pública
    Route::get('/viajes', [ViajeController::class, 'index'])->name('viajes.index');
    Route::get('/viajes/{id}', [ViajeController::class, 'show'])->name('viajes.show');

    // Empresas - Consulta pública
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/{id}', [EmpresaController::class, 'show'])->name('empresas.show');

    // Ciudades - Consulta pública
    Route::get('/ciudades', [CiudadController::class, 'index'])->name('ciudades.index');
    Route::get('/ciudades/{id}', [CiudadController::class, 'show'])->name('ciudades.show');

    /**
     * RUTAS PROTEGIDAS (requieren autenticación)
     * Las validaciones de rol (admin/visitante) se hacen en los Controllers
     */
    Route::middleware('auth:sanctum')->group(function () {

        // Empresas - Modificación (solo admin)
        Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
        Route::put('/empresas/{id}', [EmpresaController::class, 'update'])->name('empresas.update');
        Route::delete('/empresas/{id}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

        // Ciudades - Modificación (solo admin)
        Route::post('/ciudades', [CiudadController::class, 'store'])->name('ciudades.store');
        Route::put('/ciudades/{id}', [CiudadController::class, 'update'])->name('ciudades.update');
        Route::delete('/ciudades/{id}', [CiudadController::class, 'destroy'])->name('ciudades.destroy');

        // Viajes - Modificación (solo admin)
        Route::post('/viajes', [ViajeController::class, 'store'])->name('viajes.store');
        Route::put('/viajes/{id}', [ViajeController::class, 'update'])->name('viajes.update');
        Route::delete('/viajes/{id}', [ViajeController::class, 'destroy'])->name('viajes.destroy');
    });

    /**
     * RUTA DE SALUD (Health Check)
     */
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API Health Check OK',
            'timestamp' => now()
        ]);
    })->name('health');
});
