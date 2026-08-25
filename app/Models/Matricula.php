<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matricula extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'matriculas';

    protected $fillable = [
        'codigo_matricula',
        'estudiante_id',
        'grupo_id',
        'fecha_matricula',
        'precio_nivel_acordado',
        'cantidad_cuotas',
        'monto_mora_acordado',
        'estado',
        'aprobada_at',
        'aprobada_por',
        'fecha_retiro',
        'justificacion_retiro',
        'observaciones',
    ];

    protected $casts = [
        'fecha_matricula' => 'date',
        'precio_nivel_acordado' => 'decimal:2',
        'cantidad_cuotas' => 'integer',
        'monto_mora_acordado' => 'decimal:2',
        'aprobada_at' => 'datetime',
        'fecha_retiro' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id'
        );
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_id'
        );
    }

    public function aprobadaPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'aprobada_por'
        );
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(
            Pago::class,
            'matricula_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where(
            'estado',
            'activa'
        );
    }



    public function cuotas(): HasMany
{
    return $this->hasMany(
        MatriculaCuota::class,
        'matricula_id'
    );
}

public function historialEstados(): HasMany
{
    return $this->hasMany(
        HistorialEstadoMatricula::class,
        'matricula_id'
    );
}

public function historialCambiosGrupo(): HasMany
{
    return $this->hasMany(
        HistorialCambioGrupoMatricula::class,
        'matricula_id'
    );
}

public function calificacionFinal(): HasOne
{
    return $this->hasOne(
        CalificacionFinal::class,
        'matricula_id'
    );
}
}