<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudInscripcion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'solicitudes_inscripcion';

    protected $fillable = [
        'codigo_solicitud',
        'persona_id',
        'fuente_referencia_id',
        'fuente_referencia_otro',
        'segmento_solicitado',
        'nivel_solicitado_id',
        'nivel_autorizado_id',
        'requiere_examen_ubicacion',
        'estado',
        'enviada_at',
        'revisada_at',
        'resuelta_at',
        'revisada_por',
        'observaciones_solicitante',
        'observaciones_administracion',
        'motivo_rechazo',
        'recomienda_otro_estudiante',
    ];

    protected $casts = [
        'requiere_examen_ubicacion' => 'boolean',
        'recomienda_otro_estudiante' => 'boolean',

        'enviada_at' => 'datetime',
        'revisada_at' => 'datetime',
        'resuelta_at' => 'datetime',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'persona_id'
        );
    }

    public function fuenteReferencia(): BelongsTo
    {
        return $this->belongsTo(
            FuenteReferencia::class,
            'fuente_referencia_id'
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

    public function revisadaPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'revisada_por'
        );
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(
            Pago::class,
            'solicitud_inscripcion_id'
        );
    }

    public function responsables(): HasMany
{
    return $this->hasMany(
        SolicitudResponsable::class,
        'solicitud_inscripcion_id'
    );
}

    public function examenesUbicacion(): HasMany
    {
        return $this->hasMany(
            ExamenUbicacion::class,
            'solicitud_inscripcion_id'
        );
    }

    public function scopePendientes(
        Builder $query
    ): Builder {
        return $query->where(
            'estado',
            'pendiente'
        );
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
                    ->where(
                        'codigo_solicitud',
                        'like',
                        "%{$termino}%"
                    )
                    ->orWhereHas(
                        'persona',
                        function (Builder $personaQuery) use ($termino) {
                            $personaQuery
                                ->where(
                                    'primer_nombre',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'primer_apellido',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'numero_documento',
                                    'like',
                                    "%{$termino}%"
                                )
                                ->orWhere(
                                    'correo_personal',
                                    'like',
                                    "%{$termino}%"
                                );
                        }
                    );
            }
        );
    }
}