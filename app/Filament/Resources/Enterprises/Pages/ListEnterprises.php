<?php

namespace App\Filament\Resources\Enterprises\Pages;

use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Filament\Resources\Enterprises\EnterpriseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListEnterprises extends ListRecords
{
    public function __construct(protected EnterpriseServiceInterface $enterpriseService) {}

    protected static string $resource = EnterpriseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return $this->enterpriseService->query();
    }
}
