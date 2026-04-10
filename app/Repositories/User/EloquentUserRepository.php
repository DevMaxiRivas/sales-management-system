<?php

namespace App\Repositories\User;

use App\Contracts\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function query(): Builder
    {
        return User::query();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage, $columns);
    }

    public function findById(int $id): ?User
    {
        return $this->query()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->query()->where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return $this->query()->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->findById($id);

        return $user ? $user->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        return $user ? $user->delete() : false;
    }
}
