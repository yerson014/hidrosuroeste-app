<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocero extends Model
{
    protected $table = 'vocero';
    protected $primaryKey = 'vocero_id';
    protected $fillable = ['nombre', 'apellido', 'cedula', 'telefono', 'direccion', 'genero', 'estado'];

    public function historial()
    {
        return $this->hasMany(VoceroHistorial::class, 'vocero_id');
    }
}