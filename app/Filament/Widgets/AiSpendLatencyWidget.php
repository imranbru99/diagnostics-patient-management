<?php

namespace App\Filament\Widgets;

use App\Models\AiLog;
use Filament\Widgets\Widget;

class AiSpendLatencyWidget extends Widget
{
    protected static string $view = 'filament.widgets.ai-spend-latency-widget';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    public function getViewData(): array
    {
        $totalCalls = AiLog::count();
        $totalCost = AiLog::sum('estimated_cost_usd');
        $avgLatency = AiLog::avg('latency_ms') ?? 0;
        $failoverCount = AiLog::where('status', 'failover')->count();

        $recentLogs = AiLog::with(['branch', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return [
            'totalCalls' => $totalCalls,
            'totalCost' => $totalCost,
            'avgLatency' => round($avgLatency),
            'failoverCount' => $failoverCount,
            'recentLogs' => $recentLogs,
        ];
    }
}
