<?php

namespace App\Filament\Resources\Products\Pages;

use App\Contracts\Product\ProductServiceInterface;
use App\Filament\Resources\Products\ProductResource;
use App\Http\Requests\CreateProductRequest;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected ProductServiceInterface $productService;

    public function boot(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): \App\Models\Product
    {
        $formRequest = new CreateProductRequest();
        $formRequest->merge($data);
        $validated = $formRequest->validated();

        return $this->productService->createProduct($validated);
    }
}
