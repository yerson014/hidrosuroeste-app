<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyecto'; // Nombre de tu tabla en la DB
    protected $primaryKey = 'proyecto_id'; // Tu llave primaria personalizada

    protected $fillable = [
        'mesa_tecnica_id',
        'titulo',
        'descripcion',
        'ubicacion',
        'fecha',
        'estado',
        'usuario_id'
    ];

    /**
     * Relación con Mesa Técnica
     */
    public function mesaTecnica()
    {
        // Especificamos la FK en 'proyecto' y la PK en 'mesa_tecnica'
        return $this->belongsTo(MesaTecnica::class, 'mesa_tecnica_id', 'mesa_tecnica_id');
    }
}