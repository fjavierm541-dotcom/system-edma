<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialCalificacion extends Model
{
    protected $table = 'historial_calificaciones';

    protected $fillable = [
        'calificacion_final_id',
        'nota_anterior',
        'nota_nueva',
        'resultado_anterior',
        'resultado_nuevo',
        'observacion_anterior',
        'observacion_nueva',
        'motivo',
        'cambiado_por',
        'cambiado_at',
    ];

    protected $casts = [
        'nota_anterior' => 'decimal:2',
        'nota_nueva' => 'decimal:2',
        'cambiado_at' => 'datetime',
    ];

    public function calificacionFinal(): BelongsTo
    {
        return $this->belongsTo(
            CalificacionFinal::class,
            'calificacion_final_id'
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