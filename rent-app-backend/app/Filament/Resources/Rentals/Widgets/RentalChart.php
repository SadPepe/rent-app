<?php

namespace App\Filament\Resources\Rentals\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RentalChart extends ChartWidget
{
    protected ?string $heading = 'Динамика аренд за 30 дней';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2; // Порядок сортировки виджета на странице

    protected function getData(): array
    {
        // Выполняем запрос к БД для получения данных
        $data = Rental::query()
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            ])
            ->where('created_at', '>=', now()->subDays(30)) // Берем данные за последние 30 дней
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Готовим массивы с метками (даты) и значениями (количество)
        $labels = $data->map(fn($item) => Carbon::parse($item->date)->format('d M'));
        $values = $data->pluck('count');

        return [
            'datasets' => [
                [
                    'label' => 'Новые аренды',
                    'data' => $values,
                    'backgroundColor' => 'rgba(252, 165, 165, 0.2)',
                    'borderColor' => 'rgb(252, 165, 165)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Указываем, что это линейный график
    }
}
