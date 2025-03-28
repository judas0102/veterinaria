<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'nombre_mascota',
        'cliente',
        'motivo',
        'fecha_hora',
        'estado',
        'observaciones',
    ];

    public function mascota()
{
    return $this->belongsTo(\App\Models\Mascota::class, 'mascota_id');
}

    // Si quieres la relación con usuarios (dueños):
    /*
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    */
}
