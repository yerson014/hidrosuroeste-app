<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'correo',
        'password',
        'rol',
        'fecha_nacimiento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            /* 'password' => 'hashed', */
        ];
    }

    /**
     * Laravel busca por defecto la columna 'email'. 
     * Sobrescribimos esto para que use 'correo'.
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    /**
     * Método para verificar si el usuario tiene un rol específico.
     * Uso: if ($user->tieneRol('admin')) { ... }
     */
    public function tieneRol(string $rol): bool
    {
        return $this->rol === $rol;
    }
}