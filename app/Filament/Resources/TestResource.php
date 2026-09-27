<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Diagnostic Catalog';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General Test Information')
                ->schema([
                    Forms\Components\Select::make('test_category_id')
                        ->relationship('category', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(50)
                        ->placeholder('e.g. CBC, LFT, TSH'),
                    Forms\Components\Select::make('sample_type')
                        ->options([
                            'Blood' => 'Blood (Whole/Serum/Plasma)',
                            'Urine' => 'Urine',
                            'Stool' => 'Stool',
                            'Sputum' => 'Sputum',
                            'Swab' => 'Swab (Nasopharyngeal / Wound)',
                            'Imaging' => 'Imaging / Radiology',
                            'Tissue' => 'Biopsy / Histopathology',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('collection_tube')
                        ->placeholder('e.g. EDTA (Purple), Serum Gel (Gold)'),
                    Forms\Components\TextInput::make('tat_hours')
                        ->label('Turnaround Time (Hours)')
                        ->numeric()
                        ->default(4)
                        ->required(),
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    Forms\Components\TextInput::make('cost_price')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\Select::make('report_type')
                        ->options([
                            'numeric_tabular' => 'Numeric Tabular (Standard Pathology)',
                            'narrative' => 'Clinical Narrative (Biopsy/Cytology)',
                            'imaging_findings' => 'Imaging Findings (X-ray, USG, CT, MRI)',
                        ])
                        ->default('numeric_tabular'),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                    Forms\Components\Textarea::make('specimen_instructions')
                        ->placeholder('e.g. Fasting 8-12 hours required before blood draw.')
                        ->columnSpanFull(),
                ])->columns(3),

            Forms\Components\Section::make('Test Parameters & Biologic Reference Intervals')
                ->schema([
                    Forms\Components\Repeater::make('parameters')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->placeholder('e.g. Hemoglobin')
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('code')
                                ->placeholder('e.g. HGB'),
                            Forms\Components\TextInput::make('unit')
                                ->placeholder('e.g. g/dL, mg/dL, 10^3/uL'),
                            Forms\Components\TextInput::make('sort_order')
                                ->numeric()
                                ->default(0),
                        ])
                        ->columns(5)
                        ->columnSpanFull(),
                ]),
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sample_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tat_hours')
                    ->label('TAT')
                    ->formatStateUsing(fn ($state) => "{$state} hrs"),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test_category_id')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('sample_type'),
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
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
