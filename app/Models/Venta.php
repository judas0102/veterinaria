<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['total'];

    public function productos()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($venta) {
            // Calcular total de la venta sumando los subtotales
            $total = $venta->productos()->sum('subtotal');
            if ($venta->total != $total) {
                $venta->update(['total' => $total]);
            }

            // Reducir stock de cada producto vendido
            foreach ($venta->productos as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->decrement('stock', $detalle->cantidad);
                }
            }
        });
    }
}
