<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoPersona extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'documentos_persona';

    protected $fillable = [
        'persona_id',
        'tipo_documento',
        'nombre_original',
        'nombre_almacenado',
        'ruta_archivo',
        'extension',
        'mime_type',
        'tamano_bytes',
        'fecha_emision',
        'fecha_vencimiento',
        'verificado',
        'verificado_at',
        'verificado_por',
        'estado',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'tamano_bytes' => 'integer',
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'verificado' => 'boolean',
            'verificado_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'persona_id'
        );
    }

    public function verificador(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verificado_por'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVerificados(Builder $query): Builder
    {
        return $query->where('verificado', true);
    }

    public function scopePendientes(Builder $query): Builder
    {
        return $query->where('verificado', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTamanoLegibleAttribute(): string
    {
        if (!$this->tamano_bytes) {
            return '0 KB';
        }

        $kilobytes = $this->tamano_bytes / 1024;

        if ($kilobytes < 1024) {
            return number_format($kilobytes, 1) . ' KB';
        }

        return number_format($kilobytes / 1024, 1) . ' MB';
    }

    public function getEstaVencidoAttribute(): bool
    {
        return $this->fecha_vencimiento?->isPast() ?? false;
    }
}