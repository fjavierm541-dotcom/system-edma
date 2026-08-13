<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodoAcademico extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'periodos_academicos';

    protected $fillable = [
        'codigo',
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_matricula',
        'fecha_fin_matricula',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_inicio_matricula' => 'date',
        'fecha_fin_matricula' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function grupos(): HasMany
    {
        return $this->hasMany(
            Grupo::class,
            'periodo_academico_id'
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
                    ->orWhere('observaciones', 'like', "%{$termino}%");
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getMatriculaAbiertaAttribute(): bool
    {
        $hoy = now()->startOfDay();

        if (
            !$this->fecha_inicio_matricula ||
            !$this->fecha_fin_matricula
        ) {
            return false;
        }

        return $hoy->between(
            $this->fecha_inicio_matricula,
            $this->fecha_fin_matricula
        );
    }

    public function getEnCursoAttribute(): bool
    {
        $hoy = now()->startOfDay();

        if (
            !$this->fecha_inicio ||
            !$this->fecha_fin
        ) {
            return false;
        }

        return $hoy->between(
            $this->fecha_inicio,
            $this->fecha_fin
        );
    }
}