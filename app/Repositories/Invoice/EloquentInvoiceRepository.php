<?php

namespace App\Repositories\Invoice;

use App\Contracts\Invoice\InvoiceRepositoryInterface;
use App\DTOs\Invoice\InvoiceFilterDTO;
use App\Filters\InvoiceQueryFilter;
use App\Models\Invoice;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentInvoiceRepository extends Repository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    // public function filter(InvoiceFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    // {
    //     $query = InvoiceQueryFilter::apply($this->query(), $dto);
    //     return $paginate ? $query->paginate($perPage) : $query->get();
    // }

    public function attachProducts(Invoice $invoice, array $data): void
    {
        $invoice->products()->attach($data);
    }
}
