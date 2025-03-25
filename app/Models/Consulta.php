<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Consulta extends Model
{
    use HasFactory;

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'cita_id',
        'diagnostico',
        'tratamiento',
        'observaciones',
    ];

    /**
     * Relación: Una consulta pertenece a una cita.
     */
    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cita_id');
    }
}

