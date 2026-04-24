<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('enterprise.name'),
                TextColumn::make('invoice_number'),
                TextColumn::make('products_count')->label('Products')->counts('products'),
                TextColumn::make('total_price')->money('USD'),
                TextColumn::make('paid_at')->date(),
                TextColumn::make('created_at')->dateTime()->timeZone('America/Argentina/Buenos_Aires'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('edit')
                    ->label("Edit")
                    ->icon('heroicon-s-pencil')
                    ->color('primary')
                    ->url(
                        fn(Model $record): string
                        => InvoiceResource::getUrl('edit', ['record' => $record->getKey()])
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
