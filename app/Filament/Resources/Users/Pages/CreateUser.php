<?php

namespace App\Filament\Resources\Users\Pages;

use App\Contracts\User\UserServiceInterface;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    public function __construct(protected UserServiceInterface $userService) {}

    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): \App\Models\User
    {
        return $this->userService->createUser($data);
    }
}
