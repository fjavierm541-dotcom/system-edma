<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoDocente extends Model
{
    use HasFactory;

    protected $table = 'grupo_docentes';

    protected $fillable = [
        'grupo_id',
        'docente_id',
        'tipo_asignacion',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_id'
        );
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(
            Docente::class,
            'docente_id'
        );
    }
}