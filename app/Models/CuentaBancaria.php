<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaBancaria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'empleado_id',
        'institucion_financiera_id',
        'numero_cuenta',
        'tipo_cuenta',
        'moneda',
        'nombre_titular',
        'es_principal',
        'activo',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
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

    public function institucionFinanciera(): BelongsTo
    {
        return $this->belongsTo(
            InstitucionFinanciera::class,
            'institucion_financiera_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopePrincipales(Builder $query): Builder
    {
        return $query->where('es_principal', true);
    }

    public function scopeVigentes(Builder $query): Builder
    {
        return $query
            ->where('activo', true)
            ->where(function (Builder $subquery): void {
                $subquery
                    ->whereNull('fecha_inicio')
                    ->orWhere(
                        'fecha_inicio',
                        '<=',
                        today()
                    );
            })
            ->where(function (Builder $subquery): void {
                $subquery
                    ->whereNull('fecha_fin')
                    ->orWhere(
                        'fecha_fin',
                        '>=',
                        today()
                    );
            });
    }
}