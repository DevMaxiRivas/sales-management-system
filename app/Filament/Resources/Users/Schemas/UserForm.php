<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Model;

class UserForm
{
    public static function getRules(string $field, ?array $params, bool $create = true): array
    {
        return $create ?
            CreateUserRequest::getRulesFromField($field, $params)
            : UpdateUserRequest::getRulesFromField($field, $params);
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
                Components\TextInput::make('email')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'email',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->email()
                    ->required(),
                Components\TextInput::make('password')
                    ->rules(
                        fn(Get $get) =>
                        self::getRules(
                            field: 'password',
                            params: ['recordId' => $get('id')],
                            create: is_null($get('id'))
                        )
                    )
                    ->password()
                    ->required(static fn(?Model $record): bool => is_null($record)),
            ]);
    }
}
