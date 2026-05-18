<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('ciudad_origen_id')->constrained('ciudades')->onDelete('restrict');
            $table->foreignId('ciudad_destino_id')->constrained('ciudades')->onDelete('restrict');
            $table->foreignId('creado_por_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Datos del viaje
            $table->time('hora_salida');
            $table->time('hora_llegada');
            $table->string('tipo_servicio', 50);
            $table->decimal('precio', 10, 2);
            $table->integer('asientos_totales')->nullable();
            
            // Estado
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            
            // Observaciones
            $table->text('observaciones')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->index('empresa_id');
            $table->index('ciudad_origen_id');
            $table->index('ciudad_destino_id');
            $table->index('estado');
            $table->index('hora_salida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
