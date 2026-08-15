<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuenteReferencia extends Model
{
    use HasFactory;

    protected $table = 'fuentes_referencia';

    protected $fillable = [
        'nombre',
        'estado',
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(
            SolicitudInscripcion::class,
            'fuente_referencia_id'
        );
    }
}