<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\DTOs\Product\ProductFilterDTO;
use App\Filters\ProductQueryFilter;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Repositories\Repository;

class EloquentProductRepository extends Repository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
    public function findByBarcode(string $barcode): ?Product
    {
        return Product::query()->where('bar_code', $barcode)->first();
    }
    public function filter(ProductFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        $query = $this->query();

        ProductQueryFilter::apply($query, $dto);

        return $paginate ? $query->paginate($perPage) : $query->get();
    }
    public function getProductsByIds(array $ids): Collection
    {
        return $this->query()->whereKey($ids)->get();
    }
}
