<?php

namespace App\Repositories;

use App\Contracts\DTOs\DTOInterface;
use App\Contracts\Repository\RepositoryInterface;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function query(): Builder
    {
        return $this->model->query();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    public function findById(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    public function update(Model $product, array $data): ?Model
    {
        return $product->update($data) ? $product->refresh() : null;
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        return $product ? $product->delete() : false;
    }

    // sync() variant that only removes and adds relationships in $desiredIds 
    public function syncToModel(BelongsToMany $relation, array $currentIds, array $desiredIds): void
    {
        $toAttach = array_diff($desiredIds, $currentIds);
        $toDetach = array_diff($currentIds, $desiredIds);

        if (!empty($toAttach)) {
            $relation->attach($toAttach);
        }

        if (!empty($toDetach)) {
            $relation->detach($toDetach);
        }
    }
}
