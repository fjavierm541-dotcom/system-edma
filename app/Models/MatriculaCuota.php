<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatriculaCuota extends Model
{
    protected $table = 'matricula_cuotas';

    protected $fillable = [
        'matricula_id',
        'numero_cuota',
        'concepto',
        'monto',
        'fecha_vencimiento',
        'mora_aplicada',
        'mora_justificada',
        'estado',
        'fecha_pago_completo',
        'observaciones',
    ];

    protected $casts = [
        'numero_cuota' => 'integer',
        'monto' => 'decimal:2',
        'fecha_vencimiento' => 'date',
        'mora_aplicada' => 'decimal:2',
        'mora_justificada' => 'boolean',
        'fecha_pago_completo' => 'date',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(
            Matricula::class,
            'matricula_id'
        );
    }

    public function aplicacionesPago(): HasMany
    {
        return $this->hasMany(
            PagoCuota::class,
            'matricula_cuota_id'
        );
    }
}