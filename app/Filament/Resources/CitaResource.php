<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CitaResource\Pages;
use App\Models\Cita;
use App\Models\Mascota;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class CitaResource extends Resource
{
    protected static ?string $model = Cita::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('seleccionar_mascota')
            ->label('Seleccionar Mascota')
            ->options(
                \App\Models\Mascota::pluck('nombre de la mascota', 'id')
            )
            ->searchable()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $mascota = \App\Models\Mascota::find($state);
                if ($mascota) {
                    $set('nombre_mascota', $mascota->{'nombre de la mascota'} ?? '');
                    $set('cliente', $mascota->{'nombre del cliente'} ?? '');

                }
            }),

        Hidden::make('nombre_mascota')
            ->dehydrated()
            ->required(),

            TextInput::make('cliente')
            ->label('Nombre del Cliente')
            ->maxLength(255)
            ->required()
            ->disabled(),

            TextInput::make('motivo')
                ->label('Motivo')
                ->maxLength(255)
                ->required(),

            DateTimePicker::make('fecha_hora')
                ->label('Fecha y Hora')
                ->withoutSeconds()
                // REGLA DE VALIDACIÓN: no puede ser antes de 'ahora'
                ->rules(['after_or_equal:' . Carbon::now()->toDateTimeString()])

                ->helperText('No se permiten citas con fecha/hora en el pasado.')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('nombre_mascota')->label('Mascota')->searchable()->sortable(),
                TextColumn::make('cliente')->label('Cliente')->searchable()->sortable(),
                TextColumn::make('motivo')->searchable()->sortable(),
                TextColumn::make('fecha_hora')
                    ->label('Fecha y Hora')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCitas::route('/'),
            'create' => Pages\CreateCita::route('/create'),
            'edit'   => Pages\EditCita::route('/{record}/edit'),
        ];
    }
}
