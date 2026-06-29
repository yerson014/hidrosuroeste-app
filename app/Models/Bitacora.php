<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';
    protected $primaryKey = 'bitacora_id';
    
    // Desactivamos timestamps automáticos si la tabla solo usa el campo 'fecha'
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'accion',
        'entidad',
        'descripcion',
        'fecha',
        'ip'
    ];

    /**
     * Relación con el Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }
}