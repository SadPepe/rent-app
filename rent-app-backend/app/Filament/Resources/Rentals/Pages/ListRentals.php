<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class ListRentals extends ListRecords
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

     protected function getTableFilters(): array
    {
        return [
            Filter::make('date')
                ->form([
                    DatePicker::make('date')
                        ->label('Выберите дату'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    if (empty($data['date'])) {
                        return $query;
                    }

                    return $query->where('start_date', '<=', $data['date'])
                                 ->where('end_date', '>=', $data['date']);
                })
        ];
    }
    
}
