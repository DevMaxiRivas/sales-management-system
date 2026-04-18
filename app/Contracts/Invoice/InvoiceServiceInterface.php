<?php

namespace App\Contracts\Invoice;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoiceServiceInterface
{
    public function query(): Builder;

    public function getAllInvoices(array $columns = ['*']): Collection;

    public function paginateInvoices(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function createInvoice(array $data): Invoice;

    public function updateInvoice(int $id, array $data): Invoice;

    public function deleteInvoice(int $id): bool;

    // public function filterInvoices(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
