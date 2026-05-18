<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'ciudades';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'provincia',
        'pais',
        'estado',
    ];

    /**
     * Convertir atributos a tipos nativos.
     */
    protected function casts(): array
    {
        return [
            'estado' => 'string',
        ];
    }

    /**
     * Una ciudad tiene muchos viajes como ORIGEN.
     */
    public function viajesOrigen(): HasMany
    {
        return $this->hasMany(Viaje::class, 'ciudad_origen_id');
    }

    /**
     * Una ciudad tiene muchos viajes como DESTINO.
     */
    public function viajesDestino(): HasMany
    {
        return $this->hasMany(Viaje::class, 'ciudad_destino_id');
    }
}
