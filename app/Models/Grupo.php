<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'grupos';

    protected $fillable = [
        'nivel_id',
        'periodo_academico_id',
        'codigo',
        'nombre',
        'modalidad',
        'cupo_minimo',
        'cupo_maximo',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'cupo_minimo' => 'integer',
        'cupo_maximo' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(
            Nivel::class,
            'nivel_id'
        );
    }

    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(
            PeriodoAcademico::class,
            'periodo_academico_id'
        );
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(
            GrupoHorario::class,
            'grupo_id'
        );
    }

    public function docentes(): HasMany
    {
        return $this->hasMany(
            GrupoDocente::class,
            'grupo_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where('estado', 'inactivo');
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
                    ->where('codigo', 'like', "%{$termino}%")
                    ->orWhere('nombre', 'like', "%{$termino}%")
                    ->orWhere('modalidad', 'like', "%{$termino}%");
            }
        );
    }
}