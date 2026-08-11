<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'codigo_empleado',
        'fecha_ingreso',
        'fecha_salida',
        'cantidad_hijos',
        'institucion_laboral_actual',
        'horario_laboral_actual',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_salida' => 'date',
        'cantidad_hijos' => 'integer',
    ];

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

    public function docente(): HasOne
    {
        return $this->hasOne(
            Docente::class,
            'empleado_id'
        );
    }

    public function cuentasBancarias(): HasMany
    {
        return $this->hasMany(
            CuentaBancaria::class,
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
                        'codigo_empleado',
                        'like',
                        "%{$termino}%"
                    )
                    ->orWhere(
                        'institucion_laboral_actual',
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