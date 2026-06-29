<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencia'; // Nombre de la tabla según tu SQL
    protected $primaryKey = 'incidencia_id'; // Llave primaria personalizada

    protected $fillable = [
        'mesa_tecnica_id',
        'comunidad_id',
        'titulo',
        'tipo',
        'descripcion',
        'prioridad',
        'estado',
        'fecha',
        'usuario_id'
    ];

    /**
     * Relación con Mesa Técnica
     */
    public function mesaTecnica()
    {
        // Especificamos la FK en 'incidencia' y la PK en 'mesa_tecnica'
        return $this->belongsTo(MesaTecnica::class, 'mesa_tecnica_id', 'mesa_tecnica_id');
    }

    /**
     * Relación con Comunidad
     */
    public function comunidad()
    {
        // Especificamos la FK en 'incidencia' y la PK en 'comunidad'
        return $this->belongsTo(Comunidad::class, 'comunidad_id', 'comunidad_id');
    }
}