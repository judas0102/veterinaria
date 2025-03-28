<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MascotaResource\Pages;
use App\Filament\Resources\MascotaResource\RelationManagers;
use App\Models\Mascota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
class MascotaResource extends Resource
{
    protected static ?string $model = Mascota::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('nombre de la mascota')
                ->label('Nombre de la Mascota')
                ->required()
                ->maxLength(255),

            TextInput::make('nombre del cliente')
                ->label('Nombre del Cliente')
                ->maxLength(255),

            TextInput::make('especie')
                ->label('Especie')
                ->maxLength(255),

            TextInput::make('sexo')
                ->label('Sexo')
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')
                ->label('ID')
                ->sortable(),

            TextColumn::make('nombre de la mascota')
                ->label('Nombre de la Mascota')
                ->searchable()
                ->sortable(),

            TextColumn::make('nombre del cliente')
                ->label('Nombre del Cliente')
                ->searchable()
                ->sortable(),

            TextColumn::make('especie')
                ->searchable()
                ->sortable(),

            TextColumn::make('sexo')
                ->searchable()
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime()
                ->sortable(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListMascotas::route('/'),
            'create' => Pages\CreateMascota::route('/create'),
            'edit' => Pages\EditMascota::route('/{record}/edit'),
        ];
    }
}
