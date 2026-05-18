<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Viaje extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'empresa_id',
        'ciudad_origen_id',
        'ciudad_destino_id',
        'creado_por_id',
        'hora_salida',
        'hora_llegada',
        'tipo_servicio',
        'precio',
        'asientos_totales',
        'estado',
        'observaciones',
    ];

    /**
     * Convertir atributos a tipos nativos.
     */
    protected function casts(): array
    {
        return [
            'hora_salida' => 'string',
            'hora_llegada' => 'string',
            'precio' => 'decimal:2',
            'estado' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Un viaje pertenece a UNA empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Un viaje pertenece a UNA ciudad (origen).
     */
    public function ciudadOrigen(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_origen_id');
    }

    /**
     * Un viaje pertenece a UNA ciudad (destino).
     */
    public function ciudadDestino(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_destino_id');
    }

    /**
     * Un viaje fue creado por UN usuario.
     */
    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por_id');
    }
}
