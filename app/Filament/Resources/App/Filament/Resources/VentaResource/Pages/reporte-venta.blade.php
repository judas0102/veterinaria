<x-filament-panels::page>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Ventas</title>
        <style>
            body { font-family: sans-serif; font-size: 12px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
            th { background-color: #f0f0f0; }
        </style>
    </head>
    <body>
        <h2>Reporte de Ventas</h2>
        <p>Desde: {{ $from }} &mdash; Hasta: {{ $to }}</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Total (MXN)</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>${{ number_format($venta->total, 2) }}</td>
                    <td>{{ $venta->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
    </html>

</x-filament-panels::page>
