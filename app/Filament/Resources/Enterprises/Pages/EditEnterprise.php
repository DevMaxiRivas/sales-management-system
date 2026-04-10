<?php

namespace App\Filament\Resources\Enterprises\Pages;

use App\Filament\Resources\Enterprises\EnterpriseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEnterprise extends EditRecord
{
    protected static string $resource = EnterpriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
