<?php

namespace App\Contracts\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function query(): Builder;

    public function getAllUsers(array $columns = ['*']): Collection;

    public function paginateUsers(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function getUserByEmail(string $email): ?User;

    public function createUser(array $data): User;

    public function updateUser(int $id, array $data): User;

    public function deleteUser(int $id): bool;
}
