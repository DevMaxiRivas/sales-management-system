<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class RegisterInvoicePage extends Page
{
    public ?Invoice $record = null;
    protected static string $resource = InvoiceResource::class;
    protected string $view = 'filament.resources.invoices.pages.register-invoice-page';

    // public function mount(int|string $record): void
    // {
    //     $this->record = $this->resolveRecord($record);
    // }
}
