<?php

namespace App\Filament\Resources\Rentals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('house_id')
                    ->relationship('house', 'name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                TextInput::make('number_of_guests')
                    ->required()
                    ->numeric(),
                Select::make('status')
                ->options([
                    'pending'=> 'Ожидание',
                    'confirmed'=> 'Подтверждено',
                    'rejected' => 'Отклонено',
                    'cancelled' => 'Отменено'
                ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
