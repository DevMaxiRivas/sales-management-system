<?php

namespace App\DTOs\Enterprise;

use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use App\Enums\Filters\StringFilterMode;
use Carbon\Carbon;

class EnterpriseFilterDTO
{
    public function __construct(
        public readonly ?int $taxId = null,
        public readonly ?StringFilterMode $taxIdMode = null,
        public readonly ?Carbon $createdFrom = null,
        public readonly ?ComparisonFilterMode $createdFromMode = null,
        public readonly ?Carbon $createdUntil = null,
        public readonly ?ComparisonFilterMode $createdUntilMode = null,
        public readonly ?string $enterpriseName = null,
        public readonly ?StringFilterMode $enterpriseNameMode = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
            taxId: $filters['tax_id'] ?? null,
            taxIdMode: isset($filters['tax_id']) && isset($filters['tax_id_mode']) ?
                (StringFilterMode::tryFrom($filters['tax_id_mode']) ?? StringFilterMode::Like)
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
            enterpriseName: $filters['enterprise_name'] ?? null,
            enterpriseNameMode: isset($filters['enterprise_name']) && isset($filters['enterprise_name_mode']) ?
                (StringFilterMode::tryFrom($filters['enterprise_name_mode']) ?? StringFilterMode::Like)
                : null
        );
    }
}
