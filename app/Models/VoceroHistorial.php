<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoceroHistorial extends Model
{
    use HasFactory;

    protected $table = 'vocero_historial';
    protected $primaryKey = 'vocero_historial_id';

    protected $fillable = [
        'vocero_id',
        'mesa_tecnica_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo_salida',
        'usuario_id'
    ];

    public function vocero()
    {
        return $this->belongsTo(Vocero::class, 'vocero_id');
    }

    public function mesaTecnica()
    {
        return $this->belongsTo(MesaTecnica::class, 'mesa_tecnica_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}