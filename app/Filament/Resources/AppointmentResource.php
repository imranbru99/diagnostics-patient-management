<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Front Desk & Queue';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Appointment & Queue Details')
                ->schema([
                    Forms\Components\TextInput::make('appointment_number')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\TextInput::make('token_number')
                        ->label('Lounge Token #')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\Select::make('patient_id')
                        ->relationship('patient', 'name')
                        ->searchable(['name', 'phone', 'mrn'])
                        ->required(),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\DatePicker::make('appointment_date')
                        ->default(today())
                        ->required(),
                    Forms\Components\Select::make('time_slot')
                        ->options([
                            '08:00 - 09:00' => '08:00 AM - 09:00 AM',
                            '09:00 - 10:00' => '09:00 AM - 10:00 AM',
                            '10:00 - 11:00' => '10:00 AM - 11:00 AM',
                            '11:00 - 12:00' => '11:00 AM - 12:00 PM',
                            '14:00 - 15:00' => '02:00 PM - 03:00 PM',
                            '16:00 - 17:00' => '04:00 PM - 05:00 PM',
                        ])
                        ->required(),
                    Forms\Components\Select::make('test_id')
                        ->relationship('test', 'name')
                        ->searchable(),
                    Forms\Components\Select::make('test_package_id')
                        ->relationship('testPackage', 'name')
                        ->searchable(),
                    Forms\Components\Select::make('queue_status')
                        ->options([
                            'booked' => 'Booked (Awaiting Arrival)',
                            'checked_in' => 'Checked In (In Waiting Room)',
                            'called' => 'Called to Counter / Phlebotomy',
                            'in_service' => 'In Service / Sample Draw',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                            'no_show' => 'No Show',
                        ])
                        ->default('booked')
                        ->required(),
                    Forms\Components\Toggle::make('is_walk_in')
                        ->label('Walk-in Token (Unscheduled)'),
                    Forms\Components\Textarea::make('symptoms_chief_complaint')
                        ->label('Symptoms / Chief Complaints')
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('token_number')
                    ->label('Token')
                    ->weight('bold')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date('d-M-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_slot')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('queue_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_service' => 'purple',
                        'called' => 'warning',
                        'checked_in' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_walk_in')
                    ->label('Walk-in')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('queue_status')
                    ->options([
                        'booked' => 'Booked',
                        'checked_in' => 'Checked In',
                        'called' => 'Called',
                        'in_service' => 'In Service',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->query(fn ($query) => $query->whereDate('appointment_date', today())),
            ])
            ->actions([
                Tables\Actions\Action::make('check_in')
                    ->label('Check In')
                    ->icon('heroicon-m-arrow-right-end-on-rectangle')
                    ->color('info')
                    ->visible(fn (Appointment $record) => $record->queue_status === 'booked')
                    ->action(function (Appointment $record) {
                        $record->update(['queue_status' => 'checked_in', 'checked_in_at' => now()]);
                        Notification::make()->title('Patient Checked In to Waiting Lounge')->success()->send();
                    }),

                Tables\Actions\Action::make('call_patient')
                    ->label('Call')
                    ->icon('heroicon-m-megaphone')
                    ->color('warning')
                    ->visible(fn (Appointment $record) => $record->queue_status === 'checked_in')
                    ->action(function (Appointment $record) {
                        $record->update(['queue_status' => 'called', 'called_at' => now()]);
                        Notification::make()->title("Calling Token {$record->token_number}")->warning()->send();
                    }),

                Tables\Actions\Action::make('in_service')
                    ->label('Serve')
                    ->icon('heroicon-m-play')
                    ->color('purple')
                    ->visible(fn (Appointment $record) => $record->queue_status === 'called')
                    ->action(function (Appointment $record) {
                        $record->update(['queue_status' => 'in_service']);
                        Notification::make()->title('Sample Collection in Progress')->success()->send();
                    }),

                Tables\Actions\Action::make('complete')
                    ->label('Finish')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Appointment $record) => $record->queue_status === 'in_service')
                    ->action(function (Appointment $record) {
                        $record->update(['queue_status' => 'completed', 'completed_at' => now()]);
                        Notification::make()->title('Queue Service Completed')->success()->send();
                    }),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
