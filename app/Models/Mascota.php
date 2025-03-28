<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mascota extends Model
{
    use HasFactory;

    protected $table = 'mascotas';

    protected $fillable = [
        'nombre de la mascota',
        'nombre del cliente',
        'especie',
        'sexo',
        // agrega más campos si los tienes
    ];
}
