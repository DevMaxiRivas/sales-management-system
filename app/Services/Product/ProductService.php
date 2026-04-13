<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductServiceInterface;
use App\DTOs\Product\ProductFilterDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $repository) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function getAllProducts(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    public function paginateProducts(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function getProductById(int $id): ?Product
    {
        return $this->repository->findById($id);
    }

    public function getProductByBarcode(string $barcode): ?Product
    {
        return $this->repository->findByBarcode($barcode);
    }

    public function createProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->getProductById($id);

        if (!$product) {
            throw new \RuntimeException("Producto con id {$id} no encontrado.");
        }

        return $this->repository->update($product, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function filterProducts(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        return $this->repository->filter(
            dto: ProductFilterDTO::fromArray($filters),
            perPage: $perPage,
            paginate: $paginate
        );
    }
}
