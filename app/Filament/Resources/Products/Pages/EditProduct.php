<?php

namespace App\Filament\Resources\Products\Pages;

use App\Contracts\Product\ProductServiceInterface;
use App\Filament\Resources\Products\ProductResource;
use App\Http\Requests\UpdateProductRequest;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected ProductServiceInterface $productService;

    public function boot(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $formRequest = new UpdateProductRequest();
        $formRequest->merge($data);
        $formRequest->setRouteResolver(function () use ($record) {
            return $record;
        });
        $validated = $formRequest->validated();

        return $this->productService->updateProduct($record->getKey(), $validated);
    }
}
