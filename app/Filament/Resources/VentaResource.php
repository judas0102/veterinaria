<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VentaResource\Pages;
use App\Models\Venta;
use App\Models\Producto;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Actions\CajeroAction;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('productos')
                    ->relationship()
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->options(function (Get $get) {
                                $productosSeleccionados = collect($get('../../productos'))->pluck('producto_id')->filter();

                                // Asegurar que el producto actual también esté en las opciones para que se muestre correctamente
                                $productoActual = $get('producto_id');

                                $productos = Producto::query();

                                if ($productoActual) {
                                    $productosSeleccionados = $productosSeleccionados->filter(fn ($id) => $id != $productoActual);
                                }

                                return $productos
                                    ->when($productosSeleccionados->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $productosSeleccionados))
                                    ->pluck('nombre', 'id');
                            })
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $producto = Producto::find($state);
                                $set('precio', $producto?->precio ?? 0);

                                $cantidad = $get('cantidad') ?? 1;
                                $set('subtotal', $cantidad * ($producto?->precio ?? 0));

                                $productos = collect($get('../../productos'));
                                $productosArray = $productos->toArray();
                                $index = $get('__index');

                                foreach ($productosArray as $i => $item) {
                                    if ($i !== $index && $item['producto_id'] == $state) {
                                        $nuevaCantidad = ($item['cantidad'] ?? 1) + $cantidad;
                                        $productosArray[$i]['cantidad'] = $nuevaCantidad;
                                        $productosArray[$i]['subtotal'] = $nuevaCantidad * ($producto?->precio ?? 0);

                                        unset($productosArray[$index]);
                                        $productosArray = array_values($productosArray);

                                        $set('../../productos', $productosArray);
                                        return;
                                    }
                                }
                            }),

                        TextInput::make('cantidad')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Get $get) {
                                $producto = Producto::find($get('producto_id'));
                                $maxStock = $producto?->stock ?? 0;
                                if ($state > $maxStock) {
                                    $set('cantidad', $maxStock);
                                }
                                $set('subtotal', ($get('precio') ?? 0) * $get('cantidad'));
                            }),

                        Hidden::make('precio'),

                        TextInput::make('subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3)
                    ->mutateDehydratedStateUsing(function ($state) {
                        $agrupados = [];

                        foreach ($state as $item) {
                            $item = is_array($item) ? $item : $item->toArray();
                            $productoId = $item['producto_id'];

                            if (isset($agrupados[$productoId])) {
                                $agrupados[$productoId]['cantidad'] += $item['cantidad'];
                                $agrupados[$productoId]['subtotal'] += $item['subtotal'];
                            } else {
                                $agrupados[$productoId] = $item;
                            }
                        }

                        return array_values($agrupados);
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $total = collect($state)->sum('subtotal');
                        $set('total', $total);
                    }),

                TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->dehydrated()
                    ->afterStateHydrated(function (callable $set, Get $get) {
                        $productos = collect($get('productos'));
                        $set('total', $productos->sum('subtotal'));
                    })
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable()
                    ->getStateUsing(fn (Venta $record) => $record->productos()->sum(DB::raw('cantidad * precio'))),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Filter::make('fecha')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($query) => $query->where('created_at', '>=', $data['from']))
                        ->when($data['to'], fn ($query) => $query->where('created_at', '<=', $data['to']))
                    ),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getActions(): array
    {
        return [
            // Si no personalizaste el nombre, Filament asignará uno por defecto
            CajeroAction::make(),
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit'   => Pages\EditVenta::route('/{record}/edit'),
        ];
    }
}
