<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;


class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('nombre')->required(),
            Textarea::make('descripcion'),
            TextInput::make('precio')->numeric()->required(),
            TextInput::make('stock')->numeric()->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('nombre')->sortable()->searchable(),
            TextColumn::make('descripcion')->searchable(),
            TextColumn::make('precio')->money('USD')->sortable(),
            TextColumn::make('stock')->sortable(),
        ])
        ->filters([
            Filter::make('stock_menor_5')
                ->query(fn (Builder $query) => $query->where('stock', '<', 5))
                ->label('Stock bajo'),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
