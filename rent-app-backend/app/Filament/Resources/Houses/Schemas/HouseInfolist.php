<?php

namespace App\Filament\Resources\Houses\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
//use Filament\Infolists\Components\TagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HouseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                ->columns(['default' => 1, 'lg' => 3])
                ->schema([
                    // Секция с основной информацией: занимает 2 колонки на больших экранах
                    Section::make('Основная информация')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Название'),
                            TextEntry::make('address')
                                ->label('Адрес'),
                            TextEntry::make('description')
                                ->label('Описание')
                                ->columnSpanFull(), // Всегда занимает всю ширину внутри своей секции
                            ImageEntry::make('photos')
                                ->label('Фотографии')
                                ->width('100%')
                                ->height('auto')
                                ->columnSpanFull(),
                        ])
                        // Внутри этой секции будет 2 колонки на средних экранах (md) и больше
                        ->columns(['default' => 1, 'md' => 2])
                        ->columnSpan(['lg' => 2]), // Секция занимает 2/3 ширины на больших экранах

                    // Боковая секция с деталями: занимает 1 колонку на больших экранах
                    Section::make('Детали')
                        ->schema([
                            TextEntry::make('price_per_day')
                                ->label('Цена за день')
                                ->money('RUB')
                                ->badge(),
                            IconEntry::make('is_active')
                                ->label('Статус')
                                ->boolean(),
                            TextEntry::make('bedrooms')
                                ->label('Спальни'),
                            TextEntry::make('bathrooms')
                                ->label('Ванные'),
                            TextEntry::make('max_guests')
                                ->label('Макс. гостей'),
                            TextEntry::make('amenities')
                                ->label('Удобства'),
                            TextEntry::make('created_at')
                                ->label('Создано')
                                ->dateTime(),
                            TextEntry::make('updated_at')
                                ->label('Обновлено')
                                ->dateTime(),
                        ])
                        ->columnSpan(['lg' => 1]), // Секция занимает 1/3 ширины на больших экранах
                ]),
            ]);
    }
}
