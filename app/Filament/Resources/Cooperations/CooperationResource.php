<?php

namespace App\Filament\Resources\Cooperations;

use App\Filament\Resources\Cooperations\Pages;
use App\Models\Cooperation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class CooperationResource extends Resource
{
    protected static ?string $model = Cooperation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Kerja Sama';
    protected static ?string $modelLabel = 'Kerja Sama';
    protected static ?string $pluralModelLabel = 'Kerja Sama';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 1;


    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            FileUpload::make('image')
                ->label('Logo Kerja Sama')
                ->image()
                ->directory('cooperations')
                ->visibility('public')
                ->imagePreviewHeight('150')
                ->maxSize(2048)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Logo')
                    ->disk('public')
                    ->height(60),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
    \Filament\Actions\EditAction::make(),
    \Filament\Actions\DeleteAction::make(),
])
->bulkActions([
    \Filament\Actions\DeleteBulkAction::make(),
]);
    }

    public static function getRelations(): array
    {
        return [];

    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCooperations::route('/'),
            'create' => Pages\CreateCooperation::route('/create'),
            'edit'   => Pages\EditCooperation::route('/{record}/edit'),
        ];
    }
}