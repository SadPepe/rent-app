<?php

namespace App\Filament\Resources\Houses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->columnSpan('full'),
            Textarea::make('description')
                ->columnSpan('full'),
            TextInput::make('address')
                ->required()
                ->maxLength(255)
                ->columnSpan('full'),
            FileUpload::make('photos')
                ->multiple()
                ->reorderable()
                ->image()
                ->directory('houses-photos')
                ->columnSpan('full'),
            TagsInput::make('amenities')
                ->helperText('Нажмите Enter, чтобы добавить удобство.'),
            TextInput::make('price_per_day')
                ->required()
                ->numeric()
                ->prefix('₽')
                ->helperText('Цена указывается в рублях.'),
            TextInput::make('bedrooms')
                ->required()
                ->numeric()
                ->default(1),
            TextInput::make('bathrooms')
                ->required()
                ->numeric()
                ->default(1),
            TextInput::make('max_guests')
                ->required()
                ->numeric()
                ->default(2),
            Toggle::make('is_active')
                ->required()
                ->default(true)
                ->helperText('Объявление будет видно в поиске.'),
            ]);
    }
}
