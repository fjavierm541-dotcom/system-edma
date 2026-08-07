<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstudianteResponsable extends Model
{
    use HasFactory;

    protected $table = 'estudiante_responsables';

    protected $fillable = [
        'estudiante_id',
        'responsable_persona_id',
        'parentesco',
        'es_principal',
        'recibe_notificaciones',
        'activo',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'recibe_notificaciones' => 'boolean',
        'activo' => 'boolean',
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

    public function personaResponsable(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'responsable_persona_id'
        );
    }
}