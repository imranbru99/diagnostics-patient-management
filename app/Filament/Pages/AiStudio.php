<?php

namespace App\Filament\Pages;

use App\Models\AiLog;
use App\Services\AI\DiagnosticAIService;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AiStudio extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'AI Hub & Intelligence';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.ai-studio';
    protected static ?string $title = 'AI Studio & Failover Health';

    public ?string $ocrInput = '';
    public ?array $ocrResult = null;

    public function runOcrTest(DiagnosticAIService $aiService): void
    {
        if (empty($this->ocrInput)) {
            Notification::make()->title('Please enter sample requisition text or doctor prescription')->warning()->send();
            return;
        }

        $this->ocrResult = $aiService->ocrRequisition($this->ocrInput);
        Notification::make()->title('AI Requisition Extracted')->success()->send();
    }

    public function getViewData(): array
    {
        return [
            'providers' => config('ai-hub.failover', ['gemini', 'openai', 'anthropic', 'groq', 'ollama']),
            'totalLogs' => AiLog::count(),
            'totalCost' => AiLog::sum('estimated_cost_usd'),
            'avgLatency' => round(AiLog::avg('latency_ms') ?? 0),
            'recentLogs' => AiLog::latest()->take(10)->get(),
        ];
    }
}
