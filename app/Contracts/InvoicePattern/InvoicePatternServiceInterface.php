<?php

namespace App\Contracts\InvoicePattern;

use App\Models\InvoicePattern;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoicePatternServiceInterface
{
    public function query(): Builder;

    public function getAllInvoicePatterns(array $columns = ['*']): Collection;

    public function paginateInvoicePatterns(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function createInvoicePattern(array $data): InvoicePattern;

    public function updateInvoicePattern(int $id, array $data): InvoicePattern;

    public function deleteInvoicePattern(int $id): bool;

    public function filterInvoicePatterns(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
