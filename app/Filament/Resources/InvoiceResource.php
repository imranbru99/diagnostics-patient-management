<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Front Desk & Queue';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Invoice Header')
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\Select::make('patient_id')
                        ->relationship('patient', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\Select::make('payment_method')
                        ->options([
                            'cash' => 'Cash',
                            'card' => 'Debit / Credit Card (POS)',
                            'bkash' => 'bKash Mobile Payment',
                            'nagad' => 'Nagad',
                            'truvo_pay' => 'Truvo Pay Online Gateway',
                            'insurance' => 'Corporate / Health Insurance',
                        ])
                        ->default('cash')
                        ->required(),
                    Forms\Components\TextInput::make('transaction_reference')
                        ->placeholder('TRX-ID or Card Auth Code'),
                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partially Paid',
                            'paid' => 'Fully Paid',
                            'refunded' => 'Refunded',
                        ])
                        ->required(),
                ])->columns(3),

            Forms\Components\Section::make('Payment Calculations')
                ->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    Forms\Components\TextInput::make('discount_amount')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\TextInput::make('tax_amount')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    Forms\Components\TextInput::make('net_total')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    Forms\Components\TextInput::make('paid_amount')
                        ->numeric()
                        ->prefix('$')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $net = (float) $get('net_total');
                            $paid = (float) $state;
                            $set('balance_due', max(0, $net - $paid));
                        })
                        ->required(),
                    Forms\Components\TextInput::make('balance_due')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                ])->columns(3),

            Forms\Components\Section::make('Insurance / Corporate Billing')
                ->schema([
                    Forms\Components\TextInput::make('insurance_provider')
                        ->placeholder('e.g. MetLife, Green Delta, Pragati Life'),
                    Forms\Components\TextInput::make('insurance_policy_number'),
                    Forms\Components\TextInput::make('insurance_coverage_amount')
                        ->numeric()
                        ->prefix('$'),
                    Forms\Components\Select::make('insurance_claim_status')
                        ->options([
                            'none' => 'N/A',
                            'submitted' => 'Claim Submitted',
                            'approved' => 'Approved by Insurer',
                            'rejected' => 'Rejected',
                        ])
                        ->default('none'),
                ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
                Tables\Columns\TextColumn::make('net_total')
                    ->label('Net Total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance_due')
                    ->label('Due')
                    ->money('USD')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_reconciled')
                    ->label('Reconciled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(['paid' => 'Paid', 'partial' => 'Partial', 'unpaid' => 'Unpaid']),
                Tables\Filters\SelectFilter::make('payment_method'),
                Tables\Filters\TernaryFilter::make('is_reconciled')
                    ->label('Cash Reconciled'),
            ])
            ->actions([
                Tables\Actions\Action::make('reconcile')
                    ->label('Reconcile Cash')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (Invoice $record) => !$record->is_reconciled)
                    ->action(function (Invoice $record) {
                        $record->update(['is_reconciled' => true, 'reconciled_at' => now()]);
                        Notification::make()->title('Invoice Reconciled into Cash Drawer')->success()->send();
                    }),

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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
