<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialCambioGrupoMatricula extends Model
{
    use HasFactory;

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

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function grupoAnterior()
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_anterior_id'
        );
    }

    public function grupoNuevo()
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_nuevo_id'
        );
    }

    public function cambiadoPor()
    {
        return $this->belongsTo(
            User::class,
            'cambiado_por'
        );
    }
}