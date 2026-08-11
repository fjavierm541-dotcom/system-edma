<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitucionFinanciera extends Model
{
    use HasFactory;

    protected $table = 'instituciones_financieras';

    protected $fillable = [
        'codigo',
        'nombre',
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

    public function cuentasBancarias(): HasMany
    {
        return $this->hasMany(
            CuentaBancaria::class,
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

    public function scopeInactivas(Builder $query): Builder
    {
        return $query->where('activo', false);
    }

    public function scopeOrdenadas(Builder $query): Builder
    {
        return $query->orderBy('nombre');
    }
}