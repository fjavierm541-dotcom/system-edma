<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoHorario extends Model
{
    use HasFactory;

    protected $table = 'grupo_horarios';

    protected $fillable = [
        'grupo_id',
        'horario_id',
        'dia_semana',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_id'
        );
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(
            Horario::class,
            'horario_id'
        );
    }
}