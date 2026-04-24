<?php

namespace App\Contracts\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    public function query(): Builder;

    public function getAllProducts(array $columns = ['*']): Collection;

    public function paginateProducts(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function getProductById(int $id): ?Product;

    public function getProductByBarcode(string $barcode): ?Product;

    public function createProduct(array $data): Product;

    public function updateProduct(int $id, array $data): Product;

    public function deleteProduct(int $id): bool;

    public function filterProducts(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;

    public function updateStockProducts(array $data): int;
}
