<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesaTecnica extends Model
{
    protected $table = 'mesa_tecnica';
    protected $primaryKey = 'mesa_tecnica_id';
    protected $fillable = [
        'nombre', 'fecha_creacion', 'direccion', 
        'numero_integrantes', 'estado', 'consejo_comunal_id', 'centro_asociado_id'
    ];

    public function consejoComunal()
    {
        return $this->belongsTo(ConsejoComunal::class, 'consejo_comunal_id');
    }

    public function centroAsociado()
    {
        return $this->belongsTo(CentroAsociado::class, 'centro_asociado_id');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'mesa_tecnica_id');
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'mesa_tecnica_id');
    }
}