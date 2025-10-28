<?php

namespace App\Filament\Resources\Houses\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
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
                    ->columns(1)
                    ->schema([
                        Section::make('Общая информация')
                            ->columns(1)
                            ->schema([
                                TextEntry::make('name')->label('Название'),
                                TextEntry::make('address')->label('Адрес'),
                                TextEntry::make('price_per_day')
                                    ->label('Цена за день')
                                    ->money('rub')
                                    ->badge(),
                                TextEntry::make('bedrooms')->label('Спальни')->badge()->numeric(),
                                TextEntry::make('bathrooms')->label('Ванные')->badge()->numeric(),
                                TextEntry::make('max_guests')->label('Максимум гостей')->badge()->numeric(),
                                TextEntry::make('description')
                                    ->label('Описание')
                                    ->columnSpanFull(),
                                IconEntry::make('is_active')
                                    ->label('Активен')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-badge')
                                    ->falseIcon('heroicon-o-x-mark'),
                            ]),
                        
                    ]),

                Grid::make()
                    ->columns(1)
                    ->schema([
                        ImageEntry::make('photos')
                            ->label('Главное фото')
                            ->hiddenLabel()
                            ->imageWidth('70%')
                            ->imageHeight('auto')
                        
                    ])
            ]);
    }
}
