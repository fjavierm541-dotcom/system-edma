<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialCambioGrupoMatricula extends Model
{
    protected $table = 'historial_cambios_grupo_matricula';

    protected $fillable = [
        'matricula_id',
        'grupo_anterior_id',
        'grupo_nuevo_id',
        'motivo',
        'cambiado_por',
        'cambiado_at',
    ];

    protected $casts = [
        'cambiado_at' => 'datetime',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(
            Matricula::class,
            'matricula_id'
        );
    }

    public function grupoAnterior(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_anterior_id'
        );
    }

    public function grupoNuevo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_nuevo_id'
        );
    }

    public function cambiadoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'cambiado_por'
        );
    }
}