<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Clinical Operations';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Patient Identity & Demographics')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ])
                        ->required(),
                    Forms\Components\DatePicker::make('date_of_birth')
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('age_years', \Carbon\Carbon::parse($state)->age);
                            }
                        }),
                    Forms\Components\TextInput::make('age_years')
                        ->label('Age (Years)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(120),
                    Forms\Components\Select::make('blood_group')
                        ->options([
                            'A+' => 'A+', 'A-' => 'A-',
                            'B+' => 'B+', 'B-' => 'B-',
                            'AB+' => 'AB+', 'AB-' => 'AB-',
                            'O+' => 'O+', 'O-' => 'O-',
                        ]),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->required(),
                ])->columns(3),

            Forms\Components\Section::make('Medical History & Clinical Vault')
                ->schema([
                    Forms\Components\Textarea::make('allergies')
                        ->placeholder('e.g. Penicillin, NSAIDs, Iodine-based contrast'),
                    Forms\Components\Textarea::make('medical_history')
                        ->placeholder('e.g. Type-2 Diabetes (5 yrs), Hypertension'),
                    Forms\Components\Textarea::make('address')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mrn')
                    ->label('MRN / ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('formatted_age')
                    ->label('Age'),
                Tables\Columns\TextColumn::make('blood_group')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ]),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_portal')
                    ->label('Portal')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Patient $record) => url("/portal/dashboard"))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
