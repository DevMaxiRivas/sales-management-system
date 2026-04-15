<?php

namespace App\Contracts\InvoicePattern;

use App\Contracts\Repository\RepositoryInterface;
use App\DTOs\InvoicePattern\InvoicePatternFilterDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoicePatternRepositoryInterface extends RepositoryInterface
{
    public function filter(InvoicePatternFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
