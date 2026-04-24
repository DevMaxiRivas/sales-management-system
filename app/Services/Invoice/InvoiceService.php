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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $processedData = [];
        foreach ($data["products"] as $product) {
            $processedData[$product['product_id']] = [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
            ];
        }

        return $processedData;
    }

    public function createInvoice(array $data): ?Invoice
    {
        $data['user_id'] = $this->userService->getCurrentUser()->getKey();
        $dataProducts = $this->procesedDataProducts($data);
        $record = null;
        DB::beginTransaction();
        try {
            $record = $this->repository->create($data);
            $this->repository->attachProducts($record, $dataProducts);
            $this->productService->updateStockProducts($dataProducts);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("InvoiceService - Method createInvoice: Error creating invoice \n" . $th->getMessage());
        } finally {
            return $record;
        }
    }

    public function procesedDataProductsForUpdateStock(array $data, Invoice $invoice): array
    {
        if (!isset($data["products"])) return [];

        $productsInvoice = [];
        $this->repository->getInvoiceDetails($invoice)
            ->each(
                function ($invoiceDetail) use (&$productsInvoice) {
                    return $productsInvoice[$invoiceDetail->product_id] = ["quantity" => $invoiceDetail->quantity];
                }
            );

        $dataProductsIds = array_column($data["products"], 'product_id');

        $deletedProductsIds = array_values(array_diff(array_keys($productsInvoice), $dataProductsIds));
        $updateProductsIds = array_intersect($dataProductsIds, array_keys($productsInvoice));
        $newProductsIds = array_values(array_diff($dataProductsIds, array_keys($productsInvoice)));

        $processedData = [];
        foreach ($data["products"] as $product) {
            if (in_array($product['product_id'], $updateProductsIds)) {
                $processedData[$product['product_id']] = [
                    'quantity' => $product['quantity'] - $productsInvoice[$product['product_id']]['quantity'],
                ];
            }

            if (in_array($product['product_id'], $deletedProductsIds)) {
                $processedData[$product['product_id']] = [
                    'quantity' => -$productsInvoice[$product['product_id']]['quantity'],
                ];
            }

            if (in_array($product['product_id'], $newProductsIds)) {
                $processedData[$product['product_id']] = [
                    'quantity' => $product['quantity'],
                ];
            }
        }

        return $processedData;
    }
    public function updateInvoice(int $id, array $data): ?Invoice
    {
        $record = $this->getInvoiceById($id);

        if (!$record) {
            throw new \RuntimeException("InvoiceService - Method updateInvoice: Invoice with id {$id} not found.");
        }

        DB::beginTransaction();
        try {
            $record = $this->repository->update($record, $data);
            $this->productService->updateStockProducts($this->procesedDataProductsForUpdateStock($data, $record));
            $this->repository->syncProducts($record, $this->procesedDataProducts($data));
            Log::info($record->products->toArray());
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("InvoiceService - Method updateInvoice: Error updating invoice \n" . $th->getMessage() . "\n" . $th->getTraceAsString());
            $record = null;
        } finally {
            return $record;
        }
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
