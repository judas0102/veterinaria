@extends('layouts.app')

@section('content')
<h2>Carrito de Compras</h2>
<table>
    @foreach ($carrito as $id => $producto)
        <tr>
            <td>{{ $producto['nombre'] }}</td>
            <td>${{ $producto['precio'] }}</td>
            <td>{{ $producto['cantidad'] }}</td>
            <td><form method="POST" action="/carrito/eliminar/{{ $id }}">@csrf <button>Eliminar</button></form></td>
        </tr>
    @endforeach
</table>
<form method="POST" action="/carrito/vaciar">@csrf <button>Vaciar Carrito</button></form>
@endsection
