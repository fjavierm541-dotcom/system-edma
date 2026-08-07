<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NivelEscolaridad extends Model
{
    use HasFactory;

    protected $table = 'niveles_escolaridad';

    protected $fillable = [
        'codigo',
        'nombre',
        'orden',
        'activo',
    ];

    protected $casts = [
        'orden' => 'integer',
        'activo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function estudiantes(): HasMany
    {
        return $this->hasMany(
            Estudiante::class,
            'nivel_escolaridad_id'
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

    public function scopeOrdenados(Builder $query): Builder
    {
        return $query
            ->orderByRaw('orden IS NULL')
            ->orderBy('orden')
            ->orderBy('nombre');
    }
}