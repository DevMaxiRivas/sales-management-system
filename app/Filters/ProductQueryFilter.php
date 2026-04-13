<?php

namespace App\Filters;

use App\DTOs\Product\ProductFilterDTO;
use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use App\Enums\Filters\FilterMode;
use App\Enums\Filters\ListFilterMode;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryFilter
{
    public function __construct(
        private Builder $query,
        private ProductFilterDTO $dto,
    ) {}

    public static function apply(Builder $query, ProductFilterDTO $dto): Builder
    {
        return (new self($query, $dto))
            ->applyProductIds()
            ->applyEnterprise()
            ->applyCreatedAtRange()
            ->getQuery();
    }

    private function applyProductIds(): static
    {
        if (empty($this->dto->productIds)) {
            return $this;
        }

        match ($this->dto->productIdsMode) {
            ListFilterMode::In => $this->query->whereIn('id', $this->dto->productIds),
            ListFilterMode::NotIn => $this->query->whereNotIn('id', $this->dto->productIds),
        };

        return $this;
    }

    private function applyEnterprise(): static
    {
        if (empty($this->dto->enterpriseId)) {
            return $this;
        }

        match ($this->dto->enterpriseIdMode) {
            EqualityFilterMode::Equal => $this->query->whereHas(
                'enterprises',
                fn(Builder $q) => $q->select('enterprises.id')->where('enterprises.id', $this->dto->enterpriseId)
            ),
            EqualityFilterMode::NotEqual => $this->query->whereDoesntHave(
                'enterprises',
                fn(Builder $q) => $q->select('enterprises.id')->where('enterprises.id', $this->dto->enterpriseId)
            )
        };

        return $this;
    }

    private function applyCreatedAtRange(): static
    {
        if ($this->dto->createdFrom !== null) {
            $this->query->where('created_at', '>=', $this->dto->createdFrom);
        }

        if ($this->dto->createdUntil !== null) {
            $this->query->where('created_at', '<=', $this->dto->createdUntil);
        }

        return $this;
    }

    private function getQuery(): Builder
    {
        return $this->query;
    }
}
