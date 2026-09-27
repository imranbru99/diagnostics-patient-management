<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestPackageResource\Pages;
use App\Models\TestPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestPackageResource extends Resource
{
    protected static ?string $model = TestPackage::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Diagnostic Catalog';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Health Package Setup')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g. Comprehensive Cardiac Screening'),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->placeholder('e.g. PKG-CARD-01'),
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    Forms\Components\TextInput::make('discount_percentage')
                        ->numeric()
                        ->suffix('%')
                        ->default(15),
                    Forms\Components\Toggle::make('is_popular')
                        ->label('Feature on Public Landing & Portal'),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                    Forms\Components\Select::make('tests')
                        ->relationship('tests', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tests_count')
                    ->counts('tests')
                    ->label('Tests Included')
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->formatStateUsing(fn ($state) => "{$state}% OFF")
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestPackages::route('/'),
            'create' => Pages\CreateTestPackage::route('/create'),
            'edit' => Pages\EditTestPackage::route('/{record}/edit'),
        ];
    }
}
