<?php

namespace App\Contracts\Invoice;

use App\Contracts\Repository\RepositoryInterface;
use App\DTOs\Invoice\InvoiceFilterDTO;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface extends RepositoryInterface
{
    // public function filter(InvoiceFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
    public function getInvoiceDetails(Invoice $invoice): Collection;
    public function attachProducts(Invoice $invoice, array $data): void;
    public function syncProducts(Invoice $invoice, array $data): void;
}
