<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class UpdateInvoicePage extends Page
{
    use InteractsWithRecord;

    protected static string $resource = InvoiceResource::class;

    protected string $view = 'filament.resources.invoices.pages.update-invoice-page';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
