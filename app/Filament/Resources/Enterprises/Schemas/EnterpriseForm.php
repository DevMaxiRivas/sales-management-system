<?php

namespace App\Filament\Resources\Enterprises\Schemas;

use Filament\Forms\Components;
use Filament\Schemas\Schema;
use Illuminate\View\Component;

class EnterpriseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\TextInput::make('name'),
                Components\TextInput::make('tax_id')
                    ->numeric()
                    ->minValue(
                        (int) (1 . str_repeat("0", config('config-app.enterprise_id_digits') - 1))
                    )
                    ->maxValue(
                        ((int) (1 . str_repeat("0", config('config-app.enterprise_id_digits'))) - 1)
                    )
            ]);
    }
}
