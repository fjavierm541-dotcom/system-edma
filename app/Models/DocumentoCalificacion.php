<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoCalificacion extends Model
{
    use HasFactory;

    protected $table = 'documentos_calificaciones';

    protected $fillable = [
        'grupo_id',
        'periodo_academico_id',
        'subido_por',
        'nombre_original',
        'nombre_almacenado',
        'ruta_archivo',
        'extension',
        'mime_type',
        'tamano_bytes',
        'observaciones',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function periodoAcademico()
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }

    public function subidoPor()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }
}