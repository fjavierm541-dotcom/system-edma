<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuenteReferencia extends Model
{
    use HasFactory;

    protected $table = 'fuentes_referencia';

    protected $fillable = [
        'codigo',
        'nombre',
        'requiere_especificacion',
        'activo',
    ];

    protected $casts = [
        'requiere_especificacion' => 'boolean',
        'activo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function solicitudes(): HasMany
    {
        return $this->hasMany(
            SolicitudInscripcion::class,
            'fuente_referencia_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivas(
        Builder $query
    ): Builder {
        return $query->where(
            'activo',
            true
        );
    }

    public function scopeOrdenadas(
        Builder $query
    ): Builder {
        return $query->orderBy(
            'nombre'
        );
    }
}