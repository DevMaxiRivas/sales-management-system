<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Schemas\Schema;

use Filament\Infolists;
use Filament\Schemas\Components;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Invoice Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Invoice Identifier'),

                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Register by'),

                        Infolists\Components\TextEntry::make('enterprise.name')
                            ->label('Enterprise'),

                        Infolists\Components\TextEntry::make('invoice_number')
                            ->label('Invoice Number'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d/m/Y H:i')
                            ->timeZone('America/Argentina/Buenos_Aires'),

                        Infolists\Components\TextEntry::make('paid_at')
                            ->label('Paid At')
                            ->date(),
                    ])
                    ->columns(2),

                Components\Section::make('Invoice Products')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('products')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Product')
                                    ->columnSpan(2),
                                Infolists\Components\TextEntry::make('pivot.unit_price')
                                    ->label('Unit Price')
                                    ->money('USD'),
                                Infolists\Components\TextEntry::make('pivot.quantity')
                                    ->label('Quantity')
                                    ->numeric(),
                            ])
                            ->columns(2),
                    ]),
            ])
            ->columns(1);
    }
}
