<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Schemas\Components\Utilities\Get;

class ProductForm
{
    public static function getRules(string $field, ?array $params, bool $create = true): array
    {
        return $create ?
            CreateProductRequest::getRulesFromField($field, $params)
            : UpdateProductRequest::getRulesFromField($field, $params);
    }

    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                Components\TextInput::make('name')
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'name',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                    ->required(),
                Components\TextInput::make('bar_code')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'bar_code',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->numeric(),
                Components\TextInput::make('stock')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'stock',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->numeric()
                    ->default(0),
                Components\TextInput::make('min_stock')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'min_stock',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->label('Minimum Stock')
                    ->numeric(),
                Components\TextInput::make('qty_per_bundle')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'qty_per_bundle',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->label('Quantity Per Bundle')
                    ->default(1)
                    ->numeric()
            ]);
    }
}
