<?php

namespace App\Filament\Resources\Enterprises\Pages;

use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Filament\Resources\Enterprises\EnterpriseResource;
use App\Http\Requests\UpdateEnterpriseRequest;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEnterprise extends EditRecord
{
    protected EnterpriseServiceInterface $enterpriseService;

    public function boot(EnterpriseServiceInterface $enterpriseService)
    {
        $this->enterpriseService = $enterpriseService;
    }

    protected static string $resource = EnterpriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->enterpriseService->updateEnterprise($record->getKey(), $data);
    }
}
