<?php

namespace App\Contracts\Product;

use App\DTOs\Product\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function query(): Builder;

    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function filter(ProductFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;

    public function findById(int $id): ?Product;

    public function findByBarcode(string $barcode): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): ?Product;

    public function delete(int $id): bool;
}
