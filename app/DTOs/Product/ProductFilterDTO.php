<?php

namespace App\DTOs\Product;

use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use App\Enums\Filters\FilterMode;
use App\Enums\Filters\ListFilterMode;
use Carbon\Carbon;

class ProductFilterDTO
{
    public function __construct(
        public readonly ?array $productIds = null,
        public readonly ?ListFilterMode $productIdsMode = null,
        public readonly ?int $enterpriseId = null,
        public readonly ?EqualityFilterMode $enterpriseIdMode = null,
        public readonly ?Carbon $createdFrom = null,
        public readonly ?Carbon $createdUntil = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            productIds: $filters['product_ids'] ?? null,
            productIdsMode: isset($filters['product_ids']) && isset($filters['product_ids_mode']) ?
                ListFilterMode::tryFrom($filters['product_ids_mode']) ?? ListFilterMode::In
                : null,
            enterpriseId: isset($filters['enterprise_id'])
                ? (int) $filters['enterprise_id']
                : null,
            enterpriseIdMode: isset($filters['enterprise_id']) && isset($filters['enterprise_id_mode']) ?
                (EqualityFilterMode::tryFrom($filters['enterprise_id_mode']) ?? EqualityFilterMode::Equal)
                : null,
            createdFrom: isset($filters['created_from'])
                ? Carbon::createFromTimestamp($filters['created_from'])
                : null,
            createdUntil: isset($filters['created_until'])
                ? Carbon::createFromTimestamp($filters['created_until'])
                : null,
        );
    }
}
