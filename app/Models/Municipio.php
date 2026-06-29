<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipio';
    protected $primaryKey = 'municipio_id';
    
    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación con las parroquias que pertenecen a este municipio.
     */
    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'municipio_id');
    }
}