<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampleResource\Pages;
use App\Models\Sample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SampleResource extends Resource
{
    protected static ?string $model = Sample::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Clinical Operations';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sample Barcode & Metadata')
                ->schema([
                    Forms\Components\TextInput::make('barcode')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\Select::make('patient_id')
                        ->relationship('patient', 'name')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\TextInput::make('sample_type')
                        ->required(),
                    Forms\Components\TextInput::make('collection_tube')
                        ->placeholder('e.g. EDTA, Fluoride, Serum-Sep'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'collected' => '1. Collected',
                            'in_transit' => '2. In Transit',
                            'received_at_lab' => '3. Received at Central Lab',
                            'in_testing' => '4. In Testing / On Analyzer',
                            'verified' => '5. Verified & Complete',
                            'rejected' => 'Rejected',
                        ])
                        ->required(),
                ])->columns(3),

            Forms\Components\Section::make('Rejection & Recollection Workflow')
                ->schema([
                    Forms\Components\Select::make('rejection_code')
                        ->options([
                            'HEMOLYZED' => 'Hemolyzed Blood Specimen',
                            'CLOTTED' => 'Clotted Sample in EDTA Tube',
                            'INSUFFICIENT_VOLUME' => 'Insufficient Specimen Volume (QNS)',
                            'LEAKING_CONTAINER' => 'Leaking or Contaminated Container',
                            'LABEL_MISMATCH' => 'Barcode / Patient Label Mismatch',
                            'EXPIRED_TRANSPORT' => 'Transport Delay Exceeded Stability',
                        ]),
                    Forms\Components\Toggle::make('recollection_required')
                        ->label('Trigger Recollection Notice to Phlebotomy / Patient'),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barcode')
                    ->label('Barcode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sample_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('collection_tube')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'in_testing' => 'purple',
                        'received_at_lab' => 'primary',
                        'in_transit' => 'warning',
                        'collected' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('collected_at')
                    ->dateTime('d-M-Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'collected' => 'Collected',
                        'in_transit' => 'In Transit',
                        'received_at_lab' => 'Received at Lab',
                        'in_testing' => 'In Testing',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('sample_type'),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_received')
                    ->label('Receive')
                    ->icon('heroicon-m-inbox-arrow-down')
                    ->color('info')
                    ->visible(fn (Sample $record) => in_array($record->status, ['collected', 'in_transit']))
                    ->action(function (Sample $record) {
                        $record->transitionTo(Sample::STATUS_RECEIVED_AT_LAB, auth()->id(), 'Sample received at Central Lab Reception');
                        Notification::make()->title('Sample Accessioned')->success()->send();
                    }),

                Tables\Actions\Action::make('put_in_testing')
                    ->label('Analyzer')
                    ->icon('heroicon-m-play')
                    ->color('purple')
                    ->visible(fn (Sample $record) => $record->status === Sample::STATUS_RECEIVED_AT_LAB)
                    ->action(function (Sample $record) {
                        $record->transitionTo(Sample::STATUS_IN_TESTING, auth()->id(), 'Loaded into automated diagnostic analyzer');
                        Notification::make()->title('Sample Placed in Testing')->success()->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Select::make('rejection_code')
                            ->options([
                                'HEMOLYZED' => 'Hemolyzed Blood Specimen',
                                'CLOTTED' => 'Clotted Sample in EDTA Tube',
                                'INSUFFICIENT_VOLUME' => 'Insufficient Specimen Volume (QNS)',
                                'LEAKING_CONTAINER' => 'Leaking or Contaminated Container',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->required(),
                    ])
                    ->action(function (Sample $record, array $data) {
                        $record->update([
                            'status' => Sample::STATUS_REJECTED,
                            'rejection_code' => $data['rejection_code'],
                            'rejection_reason' => $data['rejection_reason'],
                            'recollection_required' => true,
                            'recollection_requested_at' => now(),
                        ]);
                        $record->transitionTo(Sample::STATUS_REJECTED, auth()->id(), "Rejected: {$data['rejection_code']} - {$data['rejection_reason']}");
                        Notification::make()->title('Sample Rejected & Recollection Flagged')->warning()->send();
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
            'index' => Pages\ListSamples::route('/'),
            'create' => Pages\CreateSample::route('/create'),
            'edit' => Pages\EditSample::route('/{record}/edit'),
        ];
    }
}
