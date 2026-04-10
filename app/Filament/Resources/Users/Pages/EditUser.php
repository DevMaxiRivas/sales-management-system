<?php

namespace App\Filament\Resources\Users\Pages;

use App\Contracts\User\UserServiceInterface;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected UserServiceInterface $userService;

    public function boot(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->userService->updateUser($record->getKey(), $data);
    }
}
