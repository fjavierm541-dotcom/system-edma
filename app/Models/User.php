<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'persona_id',
        'username',
        'email',
        'password',
        'debe_cambiar_password',
        'activo',
        'ultimo_acceso_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultimo_acceso_at' => 'datetime',
        'debe_cambiar_password' => 'boolean',
        'activo' => 'boolean',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function estudiante(): HasOneThrough
{
    return $this->hasOneThrough(
        Estudiante::class,
        Persona::class,
        'id',
        'persona_id',
        'persona_id',
        'id'
    );
}

    public function persona(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'persona_id'
        );
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'role_user',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function tieneRol(string $rol): bool
    {
        return $this->roles()
            ->where('nombre', $rol)
            ->exists();
    }
}