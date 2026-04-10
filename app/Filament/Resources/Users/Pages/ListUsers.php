<?php

namespace App\Filament\Resources\Users\Pages;

use App\Contracts\User\UserServiceInterface;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListUsers extends ListRecords
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
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return $this->userService->query();
    }
}
