<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Select;
use App\Filament\Resources\ConsultaResource\Pages;
use App\Filament\Resources\ConsultaResource\RelationManagers;
use App\Models\Consulta;
use Filament\Forms;
use App\Models\Producto;
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

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Relación con la cita
            TextInput::make('nombre_mascota')
            ->label('Nombre de la Mascota')
            ->required(),

            Textarea::make('diagnostico')
                ->label('Diagnóstico')
                ->rows(3),

            Textarea::make('tratamiento')
                ->label('Tratamiento')
                ->rows(3),

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

            // Mostrar la mascota (desde la relación)
            TextColumn::make('nombre_mascota')
            ->label('Mascota'),

            // Diagnóstico
            TextColumn::make('diagnostico')
                ->label('Diagnóstico')
                ->limit(20) // Muestra solo 20 caracteres en la tabla
                ->sortable(),

            // Tratamiento
            TextColumn::make('tratamiento')
                ->label('Tratamiento')
                ->limit(20)
                ->sortable(),

            // Fecha de creación
            TextColumn::make('created_at')
                ->label('Creada')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            // Agrega filtros si deseas
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
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit' => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }
}
