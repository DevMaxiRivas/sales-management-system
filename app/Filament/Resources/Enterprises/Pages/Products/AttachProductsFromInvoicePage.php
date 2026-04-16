<?php

namespace App\Filament\Resources\Enterprises\Pages\Products;

use App\Filament\Resources\Enterprises\EnterpriseResource;
use App\Models\Enterprise;
use Filament\Resources\Pages\Page;

class AttachProductsFromInvoicePage extends Page
{
    protected static string $resource = EnterpriseResource::class;
    public Enterprise $record;

    protected static ?string $title = 'Attach products from invoice';

    protected string $view = 'filament.resources.enterprises.pages.products.attach-products-from-invoice-page';
}
