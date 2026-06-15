<?php
namespace App\Filament\Resources\Greetings;


use App\Filament\Resources\Greetings\Pages;
use App\Models\Greeting;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GreetingResource extends Resource
{
    protected static ?string $model = Greeting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Sambutan';
    protected static ?string $modelLabel = 'Sambutan';
    protected static ?string $pluralModelLabel = 'Sambutan';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\RichEditor::make('content')
                ->label('Isi Sambutan')
                ->required()
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('image')
                ->label('Foto Pimpinan')
                ->image()
                ->directory('greetings')
                ->visibility('public')
                ->required()
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
                    ->height(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Cuplikan')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(strip_tags($state ?? ''), 80))
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGreetings::route('/'),
            'create' => Pages\CreateGreeting::route('/create'),
            'edit'   => Pages\EditGreeting::route('/{record}/edit'),
        ];
    }
}