<?php

namespace App\Services\Enterprise;

use App\Contracts\Enterprise\EnterpriseRepositoryInterface;
use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Models\Enterprise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EnterpriseService implements EnterpriseServiceInterface
{
    public function __construct(private EnterpriseRepositoryInterface $repository) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function getAllEnterprises(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    public function paginateEnterprises(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function getEnterpriseById(int $id): ?Enterprise
    {
        return $this->repository->findById($id);
    }

    public function getEnterpriseByTaxId(string $taxId): ?Enterprise
    {
        return $this->repository->findByTaxId($taxId);
    }

    public function createEnterprise(array $data): Enterprise
    {
        return $this->repository->create($data);
    }

    public function updateEnterprise(int $id, array $data): Enterprise
    {
        $enterprise = $this->getEnterpriseById($id);

        if (!$enterprise) {
            throw new \RuntimeException("Empresa con id {$id} no encontrada.");
        }

        $enterprise->update($data);

        return $enterprise->refresh();
    }

    public function deleteEnterprise(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
