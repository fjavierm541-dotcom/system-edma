<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormacionAcademica extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'formaciones_academicas';

    protected $fillable = [
        'persona_id',
        'nivel_academico',
        'titulo_obtenido',
        'institucion_educativa',
        'pais_id',
        'anio_graduacion',
        'documento_persona_id',
        'es_principal',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'anio_graduacion' => 'integer',
        'es_principal' => 'boolean',
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

    public function pais(): BelongsTo
    {
        return $this->belongsTo(
            Pais::class,
            'pais_id'
        );
    }

    public function documentoPersona(): BelongsTo
    {
        return $this->belongsTo(
            DocumentoPersona::class,
            'documento_persona_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function scopePrincipales(Builder $query): Builder
    {
        return $query->where('es_principal', true);
    }
}