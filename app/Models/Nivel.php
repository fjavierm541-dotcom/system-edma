<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nivel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'niveles';

    protected $fillable = [
        'programa_id',
        'codigo',
        'nombre',
        'descripcion',
        'orden',
        'duracion_semanas',
        'nota_minima_aprobacion',
        'estado',
    ];

    protected $casts = [
        'orden' => 'integer',
        'duracion_semanas' => 'integer',
        'nota_minima_aprobacion' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function programa(): BelongsTo
    {
        return $this->belongsTo(
            Programa::class,
            'programa_id'
        );
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(
            Grupo::class,
            'nivel_id'
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
                    ->orWhere('descripcion', 'like', "%{$termino}%");
            }
        );
    }
}