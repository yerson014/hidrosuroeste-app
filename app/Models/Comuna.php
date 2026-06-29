<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    protected $table = 'comuna';
    protected $primaryKey = 'comuna_id';
    protected $fillable = ['nombre', 'parroquia_id', 'comunidad_id'];

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    // Nueva relación: Una Comuna pertenece a una Comunidad (Opcional)
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'comunidad_id', 'comunidad_id');
    }
}