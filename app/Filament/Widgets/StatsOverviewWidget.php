<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Report;
use App\Models\Sample;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('order_date', today())->count();
        $samplesInTesting = Sample::whereIn('status', ['in_transit', 'received_at_lab', 'in_testing'])->count();
        $criticalReports = Report::whereNotNull('ai_critical_flags')
            ->whereJsonLength('ai_critical_flags', '>', 0)
            ->whereDate('created_at', today())
            ->count();
        $todayRevenue = Invoice::whereDate('created_at', today())->sum('paid_amount');

        return [
            Stat::make("Today's Patient Orders", $todayOrders)
                ->description('Orders placed across all branches')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary')
                ->chart([7, 12, 18, 14, 22, 28, $todayOrders]),

            Stat::make('Samples in Active Pipeline', $samplesInTesting)
                ->description('Collected, in transit, or on analyzer')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('warning')
                ->chart([15, 20, 18, 25, 22, $samplesInTesting]),

            Stat::make('Critical Flags (AI Hub)', $criticalReports)
                ->description('Abnormal values requiring review')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make("Today's Net Collections", '$' . number_format($todayRevenue, 2))
                ->description('Cash, Cards & Electronic Gateways')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([300, 650, 1200, 1800, 2400, $todayRevenue]),
        ];
    }
}
