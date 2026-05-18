<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'id',
        'nombre',
        'telefono',
        'email',
        'sitio_web',
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
     * Una empresa tiene muchos viajes.
     */
    public function viajes(): HasMany
    {
        return $this->hasMany(Viaje::class, 'empresa_id');
    }
}
