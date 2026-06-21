<?php

namespace App\Filament\Resources\Aboutmes;

use App\Filament\Resources\Aboutmes\Pages;
use App\Models\Aboutme;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AboutmeResource extends Resource
{
    protected static ?string $model = Aboutme::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Profil Universitas';
    protected static ?string $modelLabel = 'Profil';
    protected static ?string $pluralModelLabel = 'Profil Universitas';
    protected static \UnitEnum|string|null $navigationGroup = 'Profil Universitas';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->label('Deskripsi Profil')
                    ->required()
                    ->rows(5)
                    ->placeholder('Tuliskan profil singkat universitas (keunggulan, fokus pendidikan, dll.)')
                    ->helperText('Deskripsi singkat tanpa formatting. Untuk konten berformat gunakan menu Sejarah.')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Foto (Multiple)')
                    ->image()
                    ->disk('public')
                    ->multiple()
                    ->reorderable()
                    ->maxFiles(5)
                    ->directory('aboutmes')
                    ->visibility('public')
                    ->imagePreviewHeight('120')
                    ->maxSize(2048)
                    ->required()
                    ->helperText('Bisa upload beberapa foto sekaligus. Maks 5 foto, masing-masing 2MB.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('public')
                    ->height(50)
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Deskripsi')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(strip_tags($state ?? ''), 100))
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutmes::route('/'),
            'create' => Pages\CreateAboutme::route('/create'),
            'edit' => Pages\EditAboutme::route('/{record}/edit'),
        ];
    }
}