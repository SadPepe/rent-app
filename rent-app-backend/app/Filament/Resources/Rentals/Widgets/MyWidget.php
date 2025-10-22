<?php

namespace App\Filament\Resources\Rentals\Widgets;

use App\Models\Rental;
use App\Models\House;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MyWidget extends StatsOverviewWidget
{
    public ?string $filter = 'month';

    protected static ?int $sort = 1;

    protected function getFilters(): array
    {
        return [
            'today' => 'Сегодня',
            'week' => 'Неделя',
            'month' => 'Месяц',
        ];
    }

    protected function getStats(): array
    {
        $periodStart = Carbon::now()->startOfDay();
        $periodEnd = Carbon::now()->endOfDay();
        $daysInPeriod = 1;

        if ($this->filter === 'week') {
            $periodStart = Carbon::now()->startOfWeek();
            $periodEnd = Carbon::now()->endOfWeek();
            $daysInPeriod = 7;
        }
        if ($this->filter === 'month') {
            $periodStart = Carbon::now()->startOfMonth();
            $periodEnd = Carbon::now()->endOfMonth();
            $daysInPeriod = $periodStart->daysInMonth;
        }

        // Optimized queries
        $bookingsStats = Rental::query()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->selectRaw('count(*) as count, sum(case when status = \'completed\' then total_price else 0 end) as revenue, count(case when status = \'completed\' then 1 else null end) as completed_count')
            ->first();

        $totalRevenue = $bookingsStats->revenue ?? 0;
        $completedBookingsCount = $bookingsStats->completed_count ?? 0;
        $bookingsCount = $bookingsStats->count ?? 0;
        $averageCheck = $completedBookingsCount > 0 ? $totalRevenue / $completedBookingsCount : 0;

        $cancellationStats = Rental::query()
            ->where('status', 'cancelled')
            ->whereBetween('updated_at', [$periodStart, $periodEnd])
            ->selectRaw('count(*) as count, sum(total_price) as sum')
            ->first();

        $cancelledCount = $cancellationStats->count ?? 0;
        $cancelledSum = $cancellationStats->sum ?? 0;

        // Occupancy calculation
        $housesCount = House::query()->where('is_active', true)->count();
        $totalBookedDays = Rental::query()
            ->whereIn('status', ['completed', 'active'])
            ->where('start_date', '<=', $periodEnd)
            ->where('end_date', '>=', $periodStart)
            ->sum(DB::raw('DATEDIFF(LEAST(end_date, \'' . $periodEnd->toDateString() . '\'), GREATEST(start_date, \'' . $periodStart->toDateString() . '\')) + 1'));

        $totalAvailableDays = $housesCount * $daysInPeriod;
        $occupancy = $totalAvailableDays > 0 ? ($totalBookedDays / $totalAvailableDays) * 100 : 0;

        return [
            Stat::make('Выручка за период', 'Р' . number_format($totalRevenue, 2))
                ->description('Выручка за ' . $this->getFilters()[$this->filter])
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($this->getChartData($periodStart, $periodEnd)),

            Stat::make('Средний чек и количество бронирований', 'Р' . number_format($averageCheck, 2) . ' / ' . $bookingsCount)
                ->description('Средний чек (по завершенным) и всего бронирований за ' . $this->getFilters()[$this->filter])
                ->descriptionIcon('heroicon-m-calculator')
                ->color('primary'),

            Stat::make('Загрузка объектов', number_format($occupancy, 2) . '%')
                ->description('Процент загрузки за ' . $this->getFilters()[$this->filter])
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('info'),

            Stat::make('Отмены бронирований', $cancelledCount . ' / Р' . number_format($cancelledSum, 2))
                ->description('Количество и сумма отмен за ' . $this->getFilters()[$this->filter])
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }

    private function getChartData(Carbon $start, Carbon $end): array
    {
        $data = [];
        $query = Rental::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end]);

        switch ($this->filter) {
            case 'today':
                $revenueByUnit = $query
                    ->selectRaw('HOUR(created_at) as unit, SUM(total_price) as total')
                    ->groupBy('unit')
                    ->pluck('total', 'unit');
                $units = range(0, 23);
                break;
            case 'week':
                $revenueByUnit = $query
                    ->selectRaw('DATE(created_at) as unit, SUM(total_price) as total')
                    ->groupBy('unit')
                    ->pluck('total', 'unit');
                $units = collect(range(0, 6))->map(fn($day) => $start->copy()->addDays($day)->toDateString());
                break;
            case 'month':
            default:
                $revenueByUnit = $query
                    ->selectRaw('DATE(created_at) as unit, SUM(total_price) as total')
                    ->groupBy('unit')
                    ->pluck('total', 'unit');
                $units = collect(range(0, $start->daysInMonth -1))->map(fn($day) => $start->copy()->addDays($day)->toDateString());
                break;
        }

        foreach ($units as $unit) {
            $data[] = $revenueByUnit[$unit] ?? 0;
        }

        return $data;
    }
}