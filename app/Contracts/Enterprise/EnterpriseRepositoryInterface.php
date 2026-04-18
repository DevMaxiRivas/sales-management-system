<?php

namespace App\Contracts\Enterprise;

use App\Contracts\Repository\RepositoryInterface;
use App\DTOs\Enterprise\EnterpriseFilterDTO;
use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface EnterpriseRepositoryInterface extends RepositoryInterface
{
    public function findByTaxId(string $taxId): ?Enterprise;

    public function attachProducts(Enterprise $enterprise, array $data): void;

    public function filter(EnterpriseFilterDTO $dto, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
