<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Filament\Resources\ProductoResource\Pages;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';



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
                TextColumn::make('precio')->money('MXN')->sortable(),
                TextColumn::make('stock')->sortable(),
            ])
            ->filters([
                Filter::make('stock_menor_5')
                    ->query(fn (Builder $query) => $query->where('stock', '<', 5))
                    ->label('Stock bajo'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    // Autorizamos según el permiso "eliminar productos"
                    ->authorize(fn () => auth()->check() && auth()->user()->can('eliminar productos')),
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
            'index'  => Pages\ListProductos::route('/'),
            'create' => Pages\CreateProducto::route('/create'),
            'edit'   => Pages\EditProducto::route('/{record}/edit'),
        ];
    }

    /*
     * Métodos para controlar acceso a este Resource.
     * Ajustamos la firma a Model|Authenticatable|null para ser compatibles
     * con Filament v3.
     */
    public static function canViewAny(): bool
    {
        return auth()->check()
            && auth()->user()->can('ver productos');
    }

    public static function canCreate(): bool
    {
        return auth()->check()
            && auth()->user()->can('crear productos');
    }

    public static function canEdit($record): bool
    {
        return auth()->check()
            && auth()->user()->can('editar productos');
    }

    public static function canDelete($record): bool
    {
        return auth()->check()
            && auth()->user()->can('eliminar productos');
    }
}
