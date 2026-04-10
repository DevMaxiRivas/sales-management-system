<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('name')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->required(),
                Components\TextInput::make('bar_code')
                    ->required()
                    ->unique(ignoreRecord: true),
                Components\TextInput::make('stock')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Components\TextInput::make('min_stock')
                    ->numeric()
                    ->label('Minimum Stock'),
            ]);
    }
}
