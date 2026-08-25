<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'docentes';

    protected $fillable = [
        'empleado_id',
        'codigo_docente',
        'especialidad',
        'fecha_inicio_docencia',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio_docencia' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(
            Empleado::class,
            'empleado_id'
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
                        'codigo_docente',
                        'like',
                        "%{$termino}%"
                    )
                    ->orWhere(
                        'especialidad',
                        'like',
                        "%{$termino}%"
                    )
                    ->orWhereHas(
                        'empleado.persona',
                        function (Builder $personaQuery) use ($termino): void {
                            $personaQuery
                                ->where(
                                    'primer_nombre',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'primer_apellido',
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
                                );
                        }
                    );
            }
        );
    }


    public function gruposAsignados(): HasMany
{
    return $this->hasMany(
        GrupoDocente::class,
        'docente_id'
    );
}
}