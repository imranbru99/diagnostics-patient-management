<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReagentResource\Pages;
use App\Models\Reagent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReagentResource extends Resource
{
    protected static ?string $model = Reagent::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Logistics & Attendance';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Reagent Specification')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g. Sysmex Cellpack DCL Diluent'),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->placeholder('e.g. REAG-DCL-01'),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('brand')
                        ->placeholder('e.g. Sysmex, Roche Diagnostics, Mindray'),
                    Forms\Components\TextInput::make('lot_number')
                        ->placeholder('e.g. LOT-20260814'),
                    Forms\Components\TextInput::make('unit')
                        ->default('Tests')
                        ->placeholder('Tests, mL, Kits, Vials'),
                ])->columns(3),

            Forms\Components\Section::make('Stock Management & Expiry Alerts')
                ->schema([
                    Forms\Components\TextInput::make('stock_quantity')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('min_alert_threshold')
                        ->label('Low Stock Warning Level')
                        ->numeric()
                        ->default(20)
                        ->required(),
                    Forms\Components\TextInput::make('unit_cost')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\DatePicker::make('expiry_date')
                        ->required(),
                    Forms\Components\TextInput::make('storage_conditions')
                        ->placeholder('e.g. Store refrigerated 2°C - 8°C. Do not freeze.')
                        ->columnSpanFull(),
                ])->columns(4),
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
                Tables\Columns\TextColumn::make('brand')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Available Stock')
                    ->formatStateUsing(fn ($record) => "{$record->stock_quantity} {$record->unit}")
                    ->color(fn (Reagent $record) => $record->isLowStock() ? 'danger' : 'success')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_alert_threshold')
                    ->label('Min Alert')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->date('d-M-Y')
                    ->sortable()
                    ->color(fn (Reagent $record) => $record->expiry_date && $record->expiry_date->isPast() ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn ($query) => $query->whereRaw('stock_quantity <= min_alert_threshold')),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name'),
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
            'index' => Pages\ListReagents::route('/'),
            'create' => Pages\CreateReagent::route('/create'),
            'edit' => Pages\EditReagent::route('/{record}/edit'),
        ];
    }
}
