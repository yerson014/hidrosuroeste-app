<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroAsociado extends Model
{
    protected $table = 'centro_asociado';
    protected $primaryKey = 'centro_asociado_id';
    protected $fillable = ['comunidad_id', 'nombre', 'tipo', 'ubicacion'];

    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
}