<?php

namespace App\Contracts\Enterprise;

use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface EnterpriseRepositoryInterface
{
    public function query(): Builder;

    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function findById(int $id): ?Enterprise;

    public function findByTaxId(string $taxId): ?Enterprise;

    public function create(array $data): Enterprise;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
