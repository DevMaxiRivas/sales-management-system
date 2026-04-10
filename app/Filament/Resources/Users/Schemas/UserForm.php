<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('name')
                    ->required(),
                Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Components\TextInput::make('password')
                    ->password()
                    ->required(static fn(?Model $record): bool => is_null($record)),
            ]);
    }
}
