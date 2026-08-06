<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tipo_documento',
        'numero_documento',
        'rtn',
        'fecha_nacimiento',
        'sexo',
        'estado_civil',
        'nacionalidad',
        'correo_personal',
        'telefono_movil',
        'telefono_fijo',
        'telefono_movil_whatsapp',
        'foto_perfil',
        'pais_residencia_id',
        'direccion',
        'ciudad_municipio',
        'departamento_estado',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'telefono_movil_whatsapp' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function paisResidencia(): BelongsTo
    {
        return $this->belongsTo(
            Pais::class,
            'pais_residencia_id'
        );
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(
            DocumentoPersona::class,
            'persona_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNombreCompletoAttribute(): string
    {
        return collect([
            $this->primer_nombre,
            $this->segundo_nombre,
            $this->primer_apellido,
            $this->segundo_apellido,
        ])
            ->filter()
            ->implode(' ');
    }

    public function getInicialesAttribute(): string
    {
        return strtoupper(
            mb_substr($this->primer_nombre ?? '', 0, 1)
            . mb_substr($this->primer_apellido ?? '', 0, 1)
        );
    }

    public function getDocumentoCompletoAttribute(): ?string
    {
        if (!$this->tipo_documento || !$this->numero_documento) {
            return null;
        }

        return "{$this->tipo_documento}: {$this->numero_documento}";
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function scopeInactivas(Builder $query): Builder
    {
        return $query->where('estado', 'inactivo');
    }

    public function scopeBuscar(
        Builder $query,
        ?string $termino
    ): Builder {
        if (blank($termino)) {
            return $query;
        }

        $termino = trim($termino);

        return $query->where(function (Builder $subquery) use ($termino) {
            $subquery
                ->where('primer_nombre', 'like', "%{$termino}%")
                ->orWhere('segundo_nombre', 'like', "%{$termino}%")
                ->orWhere('primer_apellido', 'like', "%{$termino}%")
                ->orWhere('segundo_apellido', 'like', "%{$termino}%")
                ->orWhere('numero_documento', 'like', "%{$termino}%")
                ->orWhere('rtn', 'like', "%{$termino}%")
                ->orWhere('correo_personal', 'like', "%{$termino}%")
                ->orWhere('telefono_movil', 'like', "%{$termino}%");
        });
    }
}