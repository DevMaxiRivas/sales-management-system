<?php

namespace App\Filament\Resources\Enterprises\Pages;

use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Filament\Resources\Enterprises\EnterpriseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnterprise extends CreateRecord
{
    public function __construct(protected EnterpriseServiceInterface $enterpriseService) {}

    protected static string $resource = EnterpriseResource::class;

    protected function handleRecordCreation(array $data): \App\Models\Enterprise
    {
        return $this->enterpriseService->createEnterprise($data);
    }
}
