<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pagos';

    protected $fillable = [
        'codigo_pago',
        'solicitud_inscripcion_id',
        'estudiante_id',
        'periodo_academico_id',
        'matricula_id',
        'monto_total',
        'metodo_pago',
        'fecha_pago',
        'numero_referencia',
        'estado',
        'revisado_at',
        'revisado_por',
        'motivo_rechazo',
        'observaciones',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'revisado_at' => 'datetime',
    ];

    public function solicitudInscripcion(): BelongsTo
    {
        return $this->belongsTo(
            SolicitudInscripcion::class,
            'solicitud_inscripcion_id'
        );
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id'
        );
    }

    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(
            PeriodoAcademico::class,
            'periodo_academico_id'
        );
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(
            Matricula::class,
            'matricula_id'
        );
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'revisado_por'
        );
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(
            ComprobantePago::class,
            'pago_id'
        );
    }
}