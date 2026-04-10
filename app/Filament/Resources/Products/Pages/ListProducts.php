<?php

namespace App\Filament\Resources\Products\Pages;

use App\Contracts\Product\ProductServiceInterface;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListProducts extends ListRecords
{
    public function __construct(protected ProductServiceInterface $productService) {}

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return $this->productService->query();
    }
}
