<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoCuota extends Model
{
    protected $table = 'pago_cuotas';

    protected $fillable = [
        'pago_id',
        'matricula_cuota_id',
        'monto_aplicado',
    ];

    protected $casts = [
        'monto_aplicado' => 'decimal:2',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(
            Pago::class,
            'pago_id'
        );
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(
            MatriculaCuota::class,
            'matricula_cuota_id'
        );
    }
}