<?php

namespace App\DTOs\Product;

use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use App\Enums\Filters\FilterMode;
use App\Enums\Filters\ListFilterMode;
use App\Enums\Filters\StringFilterMode;
use Carbon\Carbon;

class ProductFilterDTO
{
    public function __construct(
        public readonly ?array $productIds = null,
        public readonly ?ListFilterMode $productIdsMode = null,
        public readonly ?int $enterpriseId = null,
        public readonly ?EqualityFilterMode $enterpriseIdMode = null,
        public readonly ?Carbon $createdFrom = null,
        public readonly ?ComparisonFilterMode $createdFromMode = null,
        public readonly ?Carbon $createdUntil = null,
        public readonly ?ComparisonFilterMode $createdUntilMode = null,
        public readonly ?string $productName = null,
        public readonly ?StringFilterMode $productNameMode = null,
        public readonly ?array $productEnterpriseIds = null,
        public readonly ?ListFilterMode $productEnterpriseIdsMode = null
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
            createdFromMode: isset($filters['created_from']) && isset($filters['created_from_mode']) ?
                (ComparisonFilterMode::tryFrom($filters['created_from_mode']) ?? ComparisonFilterMode::Equal)
                : null,
            createdUntil: isset($filters['created_until'])
                ? Carbon::createFromTimestamp($filters['created_until'])
                : null,
            createdUntilMode: isset($filters['created_until']) && isset($filters['created_until_mode']) ?
                (ComparisonFilterMode::tryFrom($filters['created_until_mode']) ?? ComparisonFilterMode::Equal)
                : null,
            productName: $filters['product_name'] ?? null,
            productNameMode: isset($filters['product_name']) && isset($filters['product_name_mode']) ?
                (StringFilterMode::tryFrom($filters['product_name_mode']) ?? StringFilterMode::Like)
                : null,
            productEnterpriseIds: $filters['product_enterprise_ids'] ?? null,
            productEnterpriseIdsMode: isset($filters['product_enterprise_ids']) && isset($filters['product_enterprise_ids_mode']) ?
                (ListFilterMode::tryFrom($filters['product_enterprise_ids_mode']) ?? ListFilterMode::In)
                : null
        );
    }
}
