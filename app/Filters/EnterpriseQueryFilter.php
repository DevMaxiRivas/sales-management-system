<?php

namespace App\Filters;

use App\DTOs\Enterprise\EnterpriseFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class EnterpriseQueryFilter
{
    public function __construct(
        private Builder $query,
        private EnterpriseFilterDTO $dto,
    ) {}

    public static function apply(Builder $query, EnterpriseFilterDTO $dto): Builder
    {
        return (new self($query, $dto))
            ->applyCreatedFrom()
            ->applyCreatedUntil()
            ->applyTaxId()
            ->applyEnterpriseName()
            ->getQuery();
    }

    private function applyCreatedFrom(): static
    {
        if ($this->dto->createdFrom !== null) {
            FilterApplier::comparison(
                $this->query,
                'created_at',
                $this->dto->createdFrom,
                $this->dto->createdFromMode,
            );
        }

        return $this;
    }

    private function applyCreatedUntil(): static
    {
        if ($this->dto->createdUntil !== null) {
            FilterApplier::comparison(
                $this->query,
                'created_at',
                $this->dto->createdUntil,
                $this->dto->createdUntilMode,
            );
        }

        return $this;
    }

    private function applyTaxId(): static
    {
        if ($this->dto->taxId !== null) {
            FilterApplier::string(
                $this->query,
                'tax_id',
                $this->dto->taxId,
                $this->dto->taxIdMode,
            );
        }

        return $this;
    }

    private function applyEnterpriseName(): static
    {
        if ($this->dto->enterpriseName !== null) {
            FilterApplier::string(
                $this->query,
                'name',
                $this->dto->enterpriseName,
                $this->dto->enterpriseNameMode,
            );
        }

        return $this;
    }

    private function getQuery(): Builder
    {
        return $this->query;
    }
}
