<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Medical Referral';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Physician Credentials & Commission')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g. Prof. Dr. A. K. Azad'),
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->placeholder('e.g. DOC-001'),
                    Forms\Components\TextInput::make('designation')
                        ->placeholder('e.g. MBBS, FCPS (Cardiology), MD'),
                    Forms\Components\TextInput::make('department')
                        ->placeholder('e.g. Cardiology & Internal Medicine'),
                    Forms\Components\TextInput::make('hospital_affiliation')
                        ->placeholder('e.g. Dhaka Medical College Hospital'),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->email(),
                    Forms\Components\TextInput::make('default_commission_rate')
                        ->label('Referral Commission Rate (%)')
                        ->numeric()
                        ->default(10.00)
                        ->suffix('%')
                        ->required(),
                    Forms\Components\Select::make('payout_method')
                        ->options([
                            'Bank Transfer' => 'Bank Transfer (EFT/NPSB)',
                            'bKash' => 'bKash Merchant / Personal',
                            'Nagad' => 'Nagad',
                            'Cash' => 'Direct Cash Payment',
                        ])
                        ->default('Bank Transfer'),
                    Forms\Components\Textarea::make('payout_details')
                        ->placeholder('Account No, Bank Name, Branch Routing / Mobile Wallet No')
                        ->columnSpanFull(),
                ])->columns(3),
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
                Tables\Columns\TextColumn::make('designation')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('hospital_affiliation')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('default_commission_rate')
                    ->label('Commission')
                    ->formatStateUsing(fn ($state) => "{$state}%")
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('pending_commissions_total')
                    ->label('Accrued Payout')
                    ->money('USD')
                    ->sortable(),
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
