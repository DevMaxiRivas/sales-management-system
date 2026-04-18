<?php

namespace App\Services\Invoice;

use App\Contracts\Invoice\InvoiceRepositoryInterface;
use App\Contracts\Invoice\InvoiceServiceInterface;
use App\Contracts\Product\ProductServiceInterface;
use App\Contracts\User\UserServiceInterface;
use App\DTOs\Invoice\InvoiceFilterDTO;
use App\Models\Invoice;
use App\Services\Product\ProductService;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        private InvoiceRepositoryInterface $repository,
        private ProductServiceInterface $productService,
        private UserServiceInterface $userService
    ) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function getAllInvoices(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    public function paginateInvoices(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        return $this->repository->findById($id);
    }

    public function procesedDataProducts(array $data): array
    {
        if (!isset($data["products"])) return [];

        $productsWithBundles = array_filter($data["products"], fn($product) => isset($product["bundles_quantity"]) && $product["bundles_quantity"] > 0);

        $qtysPerBundle = $this->productService->filterProducts([
            "product_ids" => array_map(fn($product) => $product["product_id"], $productsWithBundles),
            "product_ids_mode" => "in",
        ])
            ->pluck("qty_per_bundle", "id")
            ->toArray();

        $processedData = [];
        foreach ($data["products"] as $product) {
            $processedData[$product['product_id']] = [
                'quantity' =>
                $product['quantity'] + (
                    isset($qtysPerBundle[$product['product_id']]) ?
                    $product['bundles_quantity'] * $qtysPerBundle[$product['product_id']]
                    : 0
                ),
                'unit_price' => $product['unit_price'],
            ];
        }

        return $processedData;
    }

    public function createInvoice(array $data): Invoice
    {
        $data['user_id'] = $this->userService->getCurrentUser()->getKey();
        $record = $this->repository->create($data);
        $this->repository->attachProducts($record, $this->procesedDataProducts($data));
        return $record;
    }

    public function updateInvoice(int $id, array $data): Invoice
    {
        $product = $this->getInvoiceById($id);

        if (!$product) {
            throw new \RuntimeException("Invoiceo con id {$id} no encontrado.");
        }

        return $this->repository->update($product, $data);
    }

    public function deleteInvoice(int $id): bool
    {
        return $this->repository->delete($id);
    }

    // public function filterInvoices(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    // {
    //     return $this->repository->filter(
    //         dto: InvoiceFilterDTO::fromArray($filters),
    //         perPage: $perPage,
    //         paginate: $paginate
    //     );
    // }
}
