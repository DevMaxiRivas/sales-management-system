<?php

namespace App\Contracts\Product;

use App\Contracts\Repository\RepositoryInterface;
use App\DTOs\Product\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getProductsByIds(array $ids): Collection;
    public function findByBarcode(string $barcode): ?Product;
    public function filter(ProductFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
