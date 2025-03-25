<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitaResource\Pages;
use App\Filament\Resources\CitaResource\RelationManagers;
use App\Models\Cita;
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
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
        TextInput::make('nombre_mascota')
            ->label('Nombre de la Mascota')
            ->required()
            ->maxLength(255),

        TextInput::make('cliente')
            ->label('Nombre del Cliente')
            ->required()
            ->maxLength(255),

        TextInput::make('telefono')
            ->label('Número de Contacto')
            ->tel() // Campo tipo teléfono (HTML5)
            ->maxLength(255),

        DateTimePicker::make('fecha_hora')
            ->label('Fecha y Hora')
            ->required(),

        TextInput::make('estado')
            ->label('Estado')
            ->default('pendiente')
            ->required(),

        Textarea::make('observaciones')
            ->label('Observaciones')
            ->rows(3),
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('nombre_mascota')->label('Mascota'),
            TextColumn::make('fecha_hora')
                ->label('Fecha y Hora')
                ->dateTime()
                ->sortable(),
            TextColumn::make('estado')->label('Estado')->sortable(),
            TextColumn::make('created_at')
                ->label('Creado')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            // Aquí puedes agregar filtros, si los necesitas
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
            'index' => Pages\ListCitas::route('/'),
            'create' => Pages\CreateCita::route('/create'),
            'edit' => Pages\EditCita::route('/{record}/edit'),
        ];
    }
}
