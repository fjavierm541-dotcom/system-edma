<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenUbicacion extends Model
{
    use HasFactory;

    protected $table = 'examenes_ubicacion';

    protected $fillable = [
        'estudiante_id',
        'solicitud_inscripcion_id',
        'nivel_solicitado_id',
        'nivel_autorizado_id',
        'numero_intento',
        'fecha_programada',
        'fecha_realizacion',
        'calificacion',
        'estado',
        'evaluado_por',
        'resultado_observaciones',
    ];

    protected $casts = [
        'numero_intento' => 'integer',
        'fecha_programada' => 'datetime',
        'fecha_realizacion' => 'datetime',
        'calificacion' => 'decimal:2',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id'
        );
    }

    public function solicitudInscripcion(): BelongsTo
    {
        return $this->belongsTo(
            SolicitudInscripcion::class,
            'solicitud_inscripcion_id'
        );
    }

    public function nivelSolicitado(): BelongsTo
    {
        return $this->belongsTo(
            Nivel::class,
            'nivel_solicitado_id'
        );
    }

    public function nivelAutorizado(): BelongsTo
    {
        return $this->belongsTo(
            Nivel::class,
            'nivel_autorizado_id'
        );
    }

    public function evaluadoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'evaluado_por'
        );
    }
}