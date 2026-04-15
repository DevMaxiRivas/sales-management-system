<?php

namespace App\Repositories\InvoicePattern;

use App\Contracts\InvoicePattern\InvoicePatternRepositoryInterface;
use App\DTOs\InvoicePattern\InvoicePatternFilterDTO;
use App\Filters\InvoicePatternQueryFilter;
use App\Models\InvoicePattern;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentInvoicePatternRepository extends Repository implements InvoicePatternRepositoryInterface
{
    public function __construct(InvoicePattern $model)
    {
        parent::__construct($model);
    }

    public function filter(InvoicePatternFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        $query = InvoicePatternQueryFilter::apply($this->query(), $dto);
        return $paginate ? $query->paginate($perPage) : $query->get();
    }
}
