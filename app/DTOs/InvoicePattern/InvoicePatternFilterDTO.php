<?php

namespace App\DTOs\InvoicePattern;

use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use Carbon\Carbon;

class InvoicePatternFilterDTO
{
    public function __construct(
        public readonly ?int $enterpriseId = null,
        public readonly ?EqualityFilterMode $enterpriseIdMode = null,
        public readonly ?Carbon $createdFrom = null,
        public readonly ?ComparisonFilterMode $createdFromMode = null,
        public readonly ?Carbon $createdUntil = null,
        public readonly ?ComparisonFilterMode $createdUntilMode = null,
    ) {}

    public static function fromArray(array $filters): self
    {
        return new self(
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
        );
    }
}
