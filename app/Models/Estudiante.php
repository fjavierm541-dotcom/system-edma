<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiante extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'estudiantes';

    protected $fillable = [
        'persona_id',
        'nivel_escolaridad_id',
        'nivel_autorizado_id',
        'codigo_estudiante',
        'profesion_ocupacion',
        'fecha_ingreso',
        'estado',
        'observaciones',
        'nivel_autorizado_id',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function nivelAutorizado(): BelongsTo
{
    return $this->belongsTo(
        Nivel::class,
        'nivel_autorizado_id'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'persona_id'
        );
    }

    public function nivelEscolaridad(): BelongsTo
    {
        return $this->belongsTo(
            NivelEscolaridad::class,
            'nivel_escolaridad_id'
        );
    }

    public function responsables(): HasMany
    {
        return $this->hasMany(
            EstudianteResponsable::class,
            'estudiante_id'
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
            function (Builder $subquery) use ($termino): void {
                $subquery
                    ->where(
                        'codigo_estudiante',
                        'like',
                        "%{$termino}%"
                    )
                    ->orWhereHas(
                        'persona',
                        function (Builder $personaQuery) use ($termino): void {
                            $personaQuery
                                ->where(
                                    'primer_nombre',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'segundo_nombre',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'primer_apellido',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'segundo_apellido',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'numero_documento',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'correo_personal',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'telefono_movil',
                                    'like',
                                    "%{$termino}%"
                                );
                        }
                    );
            }
        );
    }
}