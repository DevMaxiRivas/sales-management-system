<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function query(): Builder
    {
        return Product::query();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage, $columns);
    }

    public function findById(int $id): ?Product
    {
        return Product::query()->find($id);
    }

    public function findByBarcode(string $barcode): ?Product
    {
        return Product::query()->where('bar_code', $barcode)->first();
    }

    public function create(array $data): Product
    {
        return Product::query()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->findById($id);

        return $product ? $product->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);

        return $product ? $product->delete() : false;
    }
}
