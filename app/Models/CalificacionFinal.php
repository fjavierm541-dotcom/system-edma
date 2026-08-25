<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalificacionFinal extends Model
{
    protected $table = 'calificaciones_finales';

    protected $fillable = [
        'matricula_id',
        'nota_final',
        'resultado',
        'observacion_docente',
        'estado',
        'registrada_por',
        'registrada_at',
        'confirmada_at',
        'bloqueada_at',
    ];

    protected $casts = [
        'nota_final' => 'decimal:2',
        'registrada_at' => 'datetime',
        'confirmada_at' => 'datetime',
        'bloqueada_at' => 'datetime',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(
            Matricula::class,
            'matricula_id'
        );
    }

    public function registradaPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'registrada_por'
        );
    }

    public function historial(): HasMany
    {
        return $this->hasMany(
            HistorialCalificacion::class,
            'calificacion_final_id'
        );
    }
}