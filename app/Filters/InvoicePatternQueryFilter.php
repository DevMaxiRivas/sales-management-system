<?php

namespace App\Filters;

use App\DTOs\InvoicePattern\InvoicePatternFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class InvoicePatternQueryFilter
{
    public function __construct(
        private Builder $query,
        private InvoicePatternFilterDTO $dto,
    ) {}

    public static function apply(Builder $query, InvoicePatternFilterDTO $dto): Builder
    {
        return (new self($query, $dto))
            ->applyEnterprise()
            ->applyCreatedFrom()
            ->applyCreatedUntil()
            ->getQuery();
    }

    private function applyEnterprise(): static
    {
        if ($this->dto->enterpriseId !== null) {
            FilterApplier::relationship(
                $this->query,
                'enterprise',
                'enterprises.id',
                $this->dto->enterpriseId,
                $this->dto->enterpriseIdMode,
            );
        }

        return $this;
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

    private function getQuery(): Builder
    {
        return $this->query;
    }
}
