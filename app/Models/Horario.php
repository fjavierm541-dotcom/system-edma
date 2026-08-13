<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'nombre',
        'hora_inicio',
        'hora_fin',
        'zona_horaria',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function grupoHorarios(): HasMany
    {
        return $this->hasMany(
            GrupoHorario::class,
            'horario_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where('activo', false);
    }

    public function scopeBuscar(
        Builder $query,
        ?string $termino
    ): Builder {
        if (blank($termino)) {
            return $query;
        }

        $termino = trim($termino);

        return $query->where(
            function (Builder $subquery) use ($termino) {
                $subquery
                    ->where('nombre', 'like', "%{$termino}%")
                    ->orWhere(
                        'zona_horaria',
                        'like',
                        "%{$termino}%"
                    );
            }
        );
    }
}