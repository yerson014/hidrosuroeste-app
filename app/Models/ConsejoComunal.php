<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsejoComunal extends Model
{
    protected $table = 'consejo_comunal';
    protected $primaryKey = 'consejo_comunal_id';
    protected $fillable = ['comunidad_id', 'nombre', 'lider_nombre', 'lider_telefono'];

    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id');
    }
}