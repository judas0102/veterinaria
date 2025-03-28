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
use App\Filament\Resources\VentaResource\Pages\ReporteVentas;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('productos')
                    ->relationship()
                    ->schema([
                        Select::make('producto_id')
                            ->label('Producto')
                            ->options(function () {
                                return Producto::all()->pluck('nombre', 'id');
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

                                if (collect($productosArray)->where('producto_id', $state)->count() > 1) {
                                    $set('producto_id', null);
                                    \Filament\Notifications\Notification::make()
                                        ->title('Error')
                                        ->body('Este producto ya fue seleccionado.')
                                        ->danger()
                                        ->send();
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
                            ->dehydrated()
                            ->reactive(),
                    ])
                    ->columns(3)
                    ->afterStateUpdated(function ($state, callable $set, Get $get) {
                        $productos = collect($get('productos'));
                        $total = $productos->sum('subtotal');
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
            'create' => Pages\CreateVenta::route('/create'),
            'edit' => Pages\EditVenta::route('/{record}/edit'),
            'reporte' => ReporteVentas::route('/reporte'),
        ];
    }
}
