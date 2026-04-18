<?php

namespace App\Contracts\Enterprise;

use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface EnterpriseServiceInterface
{
    public function query(): Builder;

    public function getAllEnterprises(array $columns = ['*']): Collection;

    public function paginateEnterprises(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function getEnterpriseById(int $id): ?Enterprise;

    public function getEnterpriseByTaxId(string $taxId): ?Enterprise;

    public function createEnterprise(array $data): Enterprise;

    public function updateEnterprise(int $id, array $data): Enterprise;

    public function deleteEnterprise(int $id): bool;

    public function attachProducts(int $id, array $data): void;

    public function filterEnterprises(array $filters, int $perPage = 15, bool $paginate = false): LengthAwarePaginator|Collection;
}
