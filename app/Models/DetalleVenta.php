<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio', 'subtotal'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::created(function ($detalleVenta) {
            $producto = Producto::find($detalleVenta->producto_id);
            if ($producto) {
                $producto->decrement('stock', $detalleVenta->cantidad);
            }
        });
    }
}
