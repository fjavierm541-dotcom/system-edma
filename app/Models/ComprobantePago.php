<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComprobantePago extends Model
{
    use HasFactory;

    protected $table = 'comprobantes_pago';

    protected $fillable = [
        'pago_id',
        'nombre_original',
        'nombre_almacenado',
        'ruta_archivo',
        'extension',
        'mime_type',
        'tamano_bytes',
    ];

    protected $casts = [
        'tamano_bytes' => 'integer',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(
            Pago::class,
            'pago_id'
        );
    }
}