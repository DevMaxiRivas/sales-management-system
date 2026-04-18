<?php

namespace App\Repositories\Enterprise;

use App\Contracts\Enterprise\EnterpriseRepositoryInterface;
use App\DTOs\Enterprise\EnterpriseFilterDTO;
use App\Filters\EnterpriseQueryFilter;
use App\Models\Enterprise;
use App\Repositories\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentEnterpriseRepository extends Repository implements EnterpriseRepositoryInterface
{
    public function __construct(Enterprise $model)
    {
        parent::__construct($model);
    }
    public function findByTaxId(string $taxId): ?Enterprise
    {
        return $this->query()->where('tax_id', $taxId)->first();
    }
    public function attachProducts(Enterprise $enterprise, array $data): void
    {
        $enterprise->Enterprises()->attach($data);
    }

    public function filter(EnterpriseFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection
    {
        $query = EnterpriseQueryFilter::apply($this->query(), $dto);

        return $paginate ? $query->paginate($perPage) : $query->get();
    }
}
