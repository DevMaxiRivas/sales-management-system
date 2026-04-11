<?php

namespace App\Contracts\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function query(): Builder;

    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function findById(int $id): ?Product;

    public function findByBarcode(string $barcode): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): ?Product;

    public function delete(int $id): bool;
}
