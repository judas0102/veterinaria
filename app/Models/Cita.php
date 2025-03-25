<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'consultas';

    protected $fillable = [
        'cita_id',
        'diagnostico',
        'tratamiento',
        'observaciones',
    ];


    // Si quieres la relación con usuarios (dueños):
    /*
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    */
}
