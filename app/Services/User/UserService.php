<?php

namespace App\Services\User;

use App\Contracts\User\UserRepositoryInterface;
use App\Contracts\User\UserServiceInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function query(): Builder
    {
        return $this->repository->query();
    }

    public function getAllUsers(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    public function paginateUsers(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function getUserById(int $id): ?User
    {
        return $this->repository->findById($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }

    public function createUser(array $data): User
    {
        return $this->repository->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        if (! $user) {
            throw new \RuntimeException("Usuario con id {$id} no encontrado.");
        }

        if (array_key_exists('password', $data) && blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return $user->refresh();
    }

    public function deleteUser(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
