<?php

namespace App\Repositories\Enterprise;

use App\Contracts\Enterprise\EnterpriseRepositoryInterface;
use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentEnterpriseRepository implements EnterpriseRepositoryInterface
{
    public function query(): Builder
    {
        return Enterprise::query();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    public function findById(int $id): ?Enterprise
    {
        return $this->query()->find($id);
    }

    public function findByTaxId(string $taxId): ?Enterprise
    {
        return $this->query()->where('tax_id', $taxId)->first();
    }

    public function create(array $data): Enterprise
    {
        return $this->query()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $enterprise = $this->findById($id);

        return $enterprise ? $enterprise->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $enterprise = $this->findById($id);

        return $enterprise ? $enterprise->delete() : false;
    }
}
