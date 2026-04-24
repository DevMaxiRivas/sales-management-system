<?php

namespace App\Contracts\Repository;

use App\Contracts\DTOs\DTOInterface;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function query(): Builder;

    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function findById(int $id): ?Model;

    public function create(array $data): Model;

    public function update(Model $product, array $data): ?Model;

    public function delete(int $id): bool;

    public function updateRecordsById(array $data, array $cols): ?int;
}
