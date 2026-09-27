<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Clinical Operations';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Information')
                ->schema([
                    Forms\Components\Select::make('patient_id')
                        ->relationship('patient', 'name')
                        ->searchable(['name', 'phone', 'mrn'])
                        ->getSearchResultsUsing(fn (string $search) => \App\Models\Patient::where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('mrn', 'like', "%{$search}%")
                            ->limit(20)
                            ->pluck('name', 'id'))
                        ->required(),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\Select::make('doctor_id')
                        ->relationship('doctor', 'name')
                        ->searchable()
                        ->label('Referring Doctor (Optional)'),
                    Forms\Components\Select::make('priority')
                        ->options([
                            'normal' => 'Normal',
                            'urgent' => 'Urgent',
                            'stat' => 'STAT (Emergency)',
                        ])
                        ->default('normal')
                        ->required(),
                    Forms\Components\DateTimePicker::make('order_date')
                        ->default(now())
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'sample_collected' => 'Sample Collected',
                            'in_lab' => 'In Laboratory',
                            'processing' => 'Processing / Testing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),
                ])->columns(3),

            Forms\Components\Section::make('Test Selection & Order Items')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('test_id')
                                ->relationship('test', 'name')
                                ->searchable()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($test = Test::find($state)) {
                                        $set('price', $test->price);
                                        $set('final_price', $test->price);
                                    }
                                }),
                            Forms\Components\TextInput::make('price')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                            Forms\Components\TextInput::make('discount')
                                ->numeric()
                                ->default(0)
                                ->prefix('$')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $price = (float) $get('price');
                                    $disc = (float) $state;
                                    $set('final_price', max(0, $price - $disc));
                                }),
                            Forms\Components\TextInput::make('final_price')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Financials & Billing')
                ->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\TextInput::make('discount_amount')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\TextInput::make('total_amount')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\TextInput::make('paid_amount')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partially Paid',
                            'paid' => 'Fully Paid',
                            'refunded' => 'Refunded',
                        ])
                        ->default('unpaid'),
                ])->columns(5),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->default('Walk-in')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'stat' => 'danger',
                        'urgent' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing', 'in_lab' => 'primary',
                        'sample_collected' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('order_date')
                    ->dateTime('d-M-Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('priority')
                    ->options(['normal' => 'Normal', 'urgent' => 'Urgent', 'stat' => 'STAT']),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'sample_collected' => 'Sample Collected',
                        'in_lab' => 'In Lab',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(['paid' => 'Paid', 'partial' => 'Partial', 'unpaid' => 'Unpaid']),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
