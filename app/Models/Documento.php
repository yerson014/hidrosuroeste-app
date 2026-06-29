<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documento';
    protected $primaryKey = 'documento_id';
    
    // Desactivamos timestamps automáticos si la tabla solo tiene 'fecha' manual o default
    public $timestamps = false;

    protected $fillable = [
        'entidad',
        'url_archivo',
        'descripcion',
        'usuario_id',
        'fecha'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'usuario_id');
    }
}