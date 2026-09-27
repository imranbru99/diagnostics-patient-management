<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\AttendanceLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = AttendanceLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = 'Logistics & Attendance';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Shift Punch Record')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required(),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                    Forms\Components\Select::make('staff_shift_id')
                        ->relationship('shift', 'name')
                        ->required(),
                    Forms\Components\DatePicker::make('attendance_date')
                        ->required(),
                    Forms\Components\DateTimePicker::make('check_in'),
                    Forms\Components\DateTimePicker::make('check_out'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'present' => 'Present',
                            'late' => 'Late Arrival',
                            'half_day' => 'Half Day',
                            'absent' => 'Absent',
                            'on_leave' => 'Authorized Leave',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('late_minutes')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('overtime_minutes')
                        ->numeric()
                        ->default(0),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attendance_date')
                    ->date('d-M-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Technician / Staff')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shift.name')
                    ->label('Shift')
                    ->badge(),
                Tables\Columns\TextColumn::make('check_in')
                    ->dateTime('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->dateTime('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'half_day' => 'info',
                        'absent' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'late' => 'Late',
                        'absent' => 'Absent',
                    ]),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
