<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidad';
    protected $primaryKey = 'comunidad_id';
    protected $fillable = [
        'parroquia_id', 'nombre', 'habitantes', 'familias', 
        'hombres', 'mujeres', 'ninos', 'usa_cisterna', 
        'agua_potable', 'zonas_silencio', 'tanques_grandes', 'sector'
    ];

    protected $casts = [
        'usa_cisterna' => 'boolean',
        'agua_potable' => 'boolean',
        'zonas_silencio' => 'boolean',
        'tanques_grandes' => 'boolean',
    ];

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    public function consejosComunales()
    {
        return $this->hasMany(ConsejoComunal::class, 'comunidad_id');
    }
}