<?php

namespace App\Services\InvoicePattern;

use App\Contracts\InvoicePattern\InvoicePatternRepositoryInterface;
use App\Contracts\InvoicePattern\InvoicePatternServiceInterface;
use App\DTOs\InvoicePattern\InvoicePatternFilterDTO;
use App\Models\InvoicePattern;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoicePatternService implements InvoicePatternServiceInterface
{
    public function __construct(private InvoicePatternRepositoryInterface $repository) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function getAllInvoicePatterns(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    public function paginateInvoicePatterns(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function getInvoicePatternById(int $id): ?InvoicePattern
    {
        return $this->repository->findById($id);
    }

    public function createInvoicePattern(array $data): InvoicePattern
    {
        return $this->repository->create($data);
    }

    public function updateInvoicePattern(int $id, array $data): InvoicePattern
    {
        $product = $this->getInvoicePatternById($id);

        if (!$product) {
            throw new \RuntimeException("InvoicePatterno con id {$id} no encontrado.");
        }

        return $this->repository->update($product, $data);
    }

    public function deleteInvoicePattern(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function filterInvoicePatterns(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        return $this->repository->filter(
            dto: InvoicePatternFilterDTO::fromArray($filters),
            perPage: $perPage,
            paginate: $paginate
        );
    }
}
