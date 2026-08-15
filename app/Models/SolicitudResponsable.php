<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudResponsable extends Model
{
    use HasFactory;

    protected $table = 'solicitud_responsables';

    protected $fillable = [
        'solicitud_inscripcion_id',
        'responsable_persona_id',
        'parentesco',
        'es_principal',
        'recibe_notificaciones',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'recibe_notificaciones' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function solicitudInscripcion(): BelongsTo
    {
        return $this->belongsTo(
            SolicitudInscripcion::class,
            'solicitud_inscripcion_id'
        );
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'responsable_persona_id'
        );
    }
}