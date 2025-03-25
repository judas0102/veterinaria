<?php

namespace App\Observers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class VentaObserver
{
    public function saved(Venta $venta)
    {
        if ($venta->productos()->exists()) {
            if ($venta->wasRecentlyCreated) {
                $venta->estado = 'pendiente';
                $venta->withoutEvents(function () use ($venta) {
                    $venta->save();
                });
            }

            $total = $venta->productos()->sum(DB::raw('cantidad * precio'));
            $venta->withoutEvents(function () use ($venta, $total) {
                $venta->update(['total' => $total]);
            });

            foreach ($venta->productos as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->decrement('stock', $detalle->cantidad);
                }
            }
        }
    }
}
