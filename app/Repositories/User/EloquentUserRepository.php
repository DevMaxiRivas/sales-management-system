<?php

namespace App\Repositories\User;

use App\Contracts\User\UserRepositoryInterface;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

class EloquentUserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        return parent::__construct($model);
    }
    public function findByEmail(string $email): ?User
    {
        return $this->query()->where('email', $email)->first();
    }
    public function getCurrentUser(): User
    {
        return Auth::user();
    }
}
