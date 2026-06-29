<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    protected $table = 'parroquia';
    protected $primaryKey = 'parroquia_id';
    protected $fillable = ['nombre', 'municipio_id'];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function comunas()
    {
        return $this->hasMany(Comuna::class, 'parroquia_id');
    }

    public function comunidades()
    {
        return $this->hasMany(Comunidad::class, 'parroquia_id');
    }
}