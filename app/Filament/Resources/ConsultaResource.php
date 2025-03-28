<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultaResource\Pages;
use App\Models\Consulta;
use App\Models\Cita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('cita_id')
            ->label('Seleccionar Cita Pendiente')
            ->relationship(
                name: 'cita',
                titleAttribute: 'nombre_mascota',
                modifyQueryUsing: fn ($query) => $query->where('estado', 'pendiente')
            )
            ->searchable()
            ->required()
            ->preload()
            ->dehydrated() // 👈 ESTA LÍNEA ES LA CLAVE
            ->getOptionLabelFromRecordUsing(
                fn ($record) => "{$record->nombre_mascota} - {$record->motivo} ({$record->fecha_hora})"
            ),

            Textarea::make('diagnostico')
                ->label('Diagnóstico')
                ->required()
                ->rows(3),

            Textarea::make('tratamiento')
                ->label('Tratamiento')
                ->required()
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
                TextColumn::make('cita.mascota.nombre')->label('Mascota'),
                TextColumn::make('cita.motivo')->label('Motivo'),
                TextColumn::make('diagnostico')->label('Diagnóstico')->limit(20),
                TextColumn::make('tratamiento')->label('Tratamiento')->limit(20),
                TextColumn::make('created_at')->label('Fecha')->dateTime()->sortable(),
            ])
            ->actions([
                EditAction::make(),
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
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit' => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }
}
