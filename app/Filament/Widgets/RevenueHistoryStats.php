<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueHistoryStats extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Revenue Last 7 Days (USD)',
                number_format(Order::where('created_at', '>=', now()->subDays(7)->startOfDay())->sum('price') / 100, 2)
            ),
            Stat::make(
                'Revenue Last 30 Days (USD)',
                number_format(Order::where('created_at', '>=', now()->subDays(30)->startOfDay())->sum('price') / 100, 2)
            ),
        ];
    }
}
