<?php

namespace App\Filament\Widgets;

use App\Models\Sample;
use Filament\Widgets\Widget;

class LiveSampleKanbanWidget extends Widget
{
    protected static string $view = 'filament.widgets.live-sample-kanban-widget';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'collected' => Sample::with(['patient', 'orderItem.test'])
                ->where('status', Sample::STATUS_COLLECTED)
                ->latest()
                ->take(6)
                ->get(),

            'in_transit' => Sample::with(['patient', 'orderItem.test'])
                ->where('status', Sample::STATUS_IN_TRANSIT)
                ->latest()
                ->take(6)
                ->get(),

            'received_at_lab' => Sample::with(['patient', 'orderItem.test'])
                ->where('status', Sample::STATUS_RECEIVED_AT_LAB)
                ->latest()
                ->take(6)
                ->get(),

            'in_testing' => Sample::with(['patient', 'orderItem.test'])
                ->where('status', Sample::STATUS_IN_TESTING)
                ->latest()
                ->take(6)
                ->get(),

            'verified' => Sample::with(['patient', 'orderItem.test'])
                ->where('status', Sample::STATUS_VERIFIED)
                ->latest()
                ->take(6)
                ->get(),
        ];
    }
}
