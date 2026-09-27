<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\User;
use App\Services\AI\DiagnosticAIService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Clinical Operations';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Report Header & Verification')
                ->schema([
                    Forms\Components\TextInput::make('report_number')
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\Select::make('patient_id')
                        ->relationship('patient', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->disabled(),
                    Forms\Components\Select::make('pathologist_id')
                        ->label('Verifying Pathologist / Radiologist')
                        ->options(fn () => User::whereIn('role', ['pathologist', 'radiologist', 'admin'])->pluck('name', 'id'))
                        ->searchable(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'preliminary' => 'Preliminary Results',
                            'verified' => 'Clinically Verified',
                            'published' => 'Published to Patient Portal',
                        ])
                        ->required(),
                    Forms\Components\DateTimePicker::make('verified_at'),
                ])->columns(3),

            Forms\Components\Section::make('Laboratory Results Entry')
                ->schema([
                    Forms\Components\Repeater::make('values')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('test_parameter_id')
                                ->relationship('parameter', 'name')
                                ->disabled()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('value_numeric')
                                ->label('Observed Value (Numeric)')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                    // Automatic reference range flag check
                                    if ($state !== null && $record && $record->parameter) {
                                        $range = $record->parameter->getMatchedReferenceRange();
                                        if ($range) {
                                            $set('flag', $range->evaluateFlag((float) $state));
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('value_text')
                                ->label('Observed Text / Notes'),
                            Forms\Components\TextInput::make('unit')
                                ->disabled(),
                            Forms\Components\TextInput::make('reference_range_snapshot')
                                ->label('Reference Interval')
                                ->disabled(),
                            Forms\Components\Select::make('flag')
                                ->options([
                                    'normal' => 'Normal',
                                    'low' => 'Low',
                                    'high' => 'High',
                                    'critical_low' => 'Critical Low',
                                    'critical_high' => 'Critical High',
                                ])
                                ->required(),
                        ])
                        ->columns(7)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Clinical Narrative & Pathologist Impression')
                ->schema([
                    Forms\Components\Textarea::make('clinical_impression')
                        ->rows(2)
                        ->placeholder('e.g. Mild microcytic hypochromic anemia. Recommend serum iron profile correlation.'),
                    Forms\Components\Textarea::make('narrative_findings')
                        ->label('Detailed Findings (for Imaging / Biopsy)')
                        ->rows(4),
                ])->columns(2),

            Forms\Components\Section::make('🤖 AI Hub Clinical Intelligence')
                ->schema([
                    Forms\Components\Textarea::make('ai_summary')
                        ->label('Patient Plain-Language Summary (Generated via AI Hub)')
                        ->rows(5)
                        ->helperText('Turns laboratory deviations into an empathetic, easy-to-understand explanation for the patient.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('ai_doctor_notes')
                        ->label("Doctor's AI Co-Pilot Notes (Advisory Only)")
                        ->rows(4)
                        ->helperText('Suggests possible differential considerations and correlation paths for the attending physician.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_number')
                    ->label('Report #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderItem.test.name')
                    ->label('Test Panel')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'verified' => 'primary',
                        'preliminary' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('has_critical')
                    ->label('Critical?')
                    ->boolean()
                    ->getStateUsing(fn (Report $record) => $record->hasCriticalValues()),
                Tables\Columns\TextColumn::make('pathologist.name')
                    ->label('Pathologist')
                    ->default('Pending'),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime('d-M-Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'preliminary' => 'Preliminary',
                        'verified' => 'Verified',
                        'published' => 'Published',
                    ]),
            ])
            ->actions([
                // 1. AI Patient Summary Generator Action
                Tables\Actions\Action::make('ai_summarize')
                    ->label('AI Summary')
                    ->icon('heroicon-m-sparkles')
                    ->color('purple')
                    ->action(function (Report $record, DiagnosticAIService $aiService) {
                        $summary = $aiService->generatePatientSummary($record);
                        $flags = $aiService->flagAbnormalValues($record);
                        $doctorNotes = $aiService->generateDoctorCopilotNotes($record);

                        $record->update([
                            'ai_summary' => $summary,
                            'ai_critical_flags' => $flags,
                            'ai_doctor_notes' => $doctorNotes,
                            'ai_analyzed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('AI Insights Generated')
                            ->body('Patient plain summary & doctor co-pilot notes created.')
                            ->success()
                            ->send();
                    }),

                // 2. Clinically Verify & Sign Off
                Tables\Actions\Action::make('verify_report')
                    ->label('Sign & Verify')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn (Report $record) => $record->status !== 'published')
                    ->action(function (Report $record) {
                        $record->update([
                            'status' => 'published',
                            'verified_at' => now(),
                            'published_at' => now(),
                            'pathologist_id' => auth()->id() ?? $record->pathologist_id,
                        ]);
                        Notification::make()->title('Report Signed & Published to Patient Portal')->success()->send();
                    }),

                // 3. View/Download PDF
                Tables\Actions\Action::make('pdf_view')
                    ->label('PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('info')
                    ->url(fn (Report $record) => route('report.pdf', $record->slug))
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
