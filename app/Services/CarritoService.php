<?php

namespace App\Services;

use App\Models\Producto;

class CarritoService
{
    public function agregarProducto($productoId, $cantidad)
    {
        $producto = Producto::findOrFail($productoId);
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $carrito[$productoId] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
            ];
        }

        session()->put('carrito', $carrito);
    }

    public function obtenerCarrito()
    {
        return session()->get('carrito', []);
    }

    public function eliminarProducto($productoId)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$productoId]);
        session()->put('carrito', $carrito);
    }

    public function vaciarCarrito()
    {
        session()->forget('carrito');
    }
}
