<?php

namespace App\Filters;

use App\DTOs\Product\ProductFilterDTO;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryFilter
{
    public function __construct(
        private Builder $query,
        private ProductFilterDTO $dto,
    ) {}

    public static function apply(Builder $query, ProductFilterDTO $dto): Builder
    {
        // $query = (new self($query, $dto))
        //     ->applyProductIds()
        //     ->applyEnterprise()
        //     ->applyProductEnterpriseIds()
        //     ->applyCreatedFrom()
        //     ->applyCreatedUntil()
        //     ->applyProductName()
        //     ->getQuery();
        // dd($query->toSql(), $query->getBindings());
        return (new self($query, $dto))
            ->applyProductIds()
            ->applyEnterprise()
            ->applyProductEnterpriseIds()
            ->applyCreatedFrom()
            ->applyCreatedUntil()
            ->applyProductName()
            ->getQuery();
    }

    private function applyProductIds(): static
    {
        if (!empty($this->dto->productIds)) {
            FilterApplier::list(
                $this->query,
                'id',
                $this->dto->productIds,
                $this->dto->productIdsMode,
            );
        }

        return $this;
    }

    private function applyEnterprise(): static
    {
        if ($this->dto->enterpriseId !== null) {
            FilterApplier::relationship(
                $this->query,
                'enterprises',
                'enterprises.id',
                $this->dto->enterpriseId,
                $this->dto->enterpriseIdMode,
            );
        }

        return $this;
    }

    private function applyProductEnterpriseIds(): static
    {
        if ($this->dto->productEnterpriseIds !== null) {
            FilterApplier::relationshipList(
                $this->query,
                'enterprises',
                'product_enterprise.product_enterprise_id',
                $this->dto->productEnterpriseIds,
                $this->dto->productEnterpriseIdsMode,
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

    private function applyProductName(): static
    {
        if ($this->dto->productName !== null) {
            FilterApplier::string(
                $this->query,
                'name',
                $this->dto->productName,
                $this->dto->productNameMode,
            );
        }

        return $this;
    }

    private function getQuery(): Builder
    {
        return $this->query;
    }
}