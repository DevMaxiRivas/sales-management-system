<?php

namespace App\Filament\Resources\Enterprises\Pages;

use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Filament\Resources\Enterprises\EnterpriseResource;
use App\Http\Requests\CreateEnterpriseRequest;
use Filament\Resources\Pages\CreateRecord;

class CreateEnterprise extends CreateRecord
{
    protected EnterpriseServiceInterface $enterpriseService;

    public function boot(EnterpriseServiceInterface $enterpriseService)
    {
        $this->enterpriseService = $enterpriseService;
    }

    protected static string $resource = EnterpriseResource::class;

    protected function handleRecordCreation(array $data): \App\Models\Enterprise
    {
        return $this->enterpriseService->createEnterprise($data);
    }
}
