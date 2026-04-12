<?php

namespace App\Filament\Resources\Enterprises\Schemas;

use App\Http\Requests\Enterprise\CreateEnterpriseRequest;
use App\Http\Requests\Enterprise\UpdateEnterpriseRequest;
use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class EnterpriseForm
{
    public static function getRules(string $field, ?array $params, bool $create = true): array
    {
        return $create ?
            CreateEnterpriseRequest::getRulesFromField($field, $params)
            : UpdateEnterpriseRequest::getRulesFromField($field, $params);
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
                    ->dehydrateStateUsing(fn($state) => strtoupper($state)),
                Components\TextInput::make('tax_id')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'tax_id',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->label('Tax ID')
                    ->numeric(),
            ]);
    }
}
