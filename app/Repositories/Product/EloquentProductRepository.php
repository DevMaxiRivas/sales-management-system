<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\DTOs\Product\ProductFilterDTO;
use App\Filters\ProductQueryFilter;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function update(Product $product, array $data): ?Product
    {
        if ($product->update($data))
            return $product->refresh();

        throw new \RuntimeException("No se pudo actualizar el registro con id {$product->id}.");
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);

        return $product ? $product->delete() : false;
    }

    public function filter(ProductFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        $query = Product::query();

        ProductQueryFilter::apply($query, $dto);

        return $paginate ? $query->paginate($perPage) : $query->get();
    }
}
