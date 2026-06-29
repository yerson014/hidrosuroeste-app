<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MesaHistorial extends Model
{
    use HasFactory;

    protected $table = 'mesa_historial';
    protected $primaryKey = 'mesa_historial_id';

    // ESTO ES LO QUE PERMITE QUE LOS DATOS SE GUARDEN
    protected $fillable = [
        'mesa_tecnica_id',
        'descripcion',
        'fecha',
        'usuario_id'
    ];

    public function mesaTecnica()
    {
        return $this->belongsTo(MesaTecnica::class, 'mesa_tecnica_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}