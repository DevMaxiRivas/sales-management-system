<?php

namespace App\Filters;

use App\Enums\Filters\ComparisonFilterMode;
use App\Enums\Filters\EqualityFilterMode;
use App\Enums\Filters\ListFilterMode;
use App\Enums\Filters\StringFilterMode;
use Illuminate\Database\Eloquent\Builder;

class FilterApplier
{
    public static function list(
        Builder $query,
        string $column,
        array $values,
        ListFilterMode $mode,
    ): void {
        match ($mode) {
            ListFilterMode::In    => $query->whereIn($column, $values),
            ListFilterMode::NotIn => $query->whereNotIn($column, $values),
        };
    }

    public static function equality(
        Builder $query,
        string $column,
        int|string $value,
        EqualityFilterMode $mode,
    ): void {
        match ($mode) {
            EqualityFilterMode::Equal    => $query->where($column, '=', $value),
            EqualityFilterMode::NotEqual => $query->where($column, '!=', $value),
        };
    }

    public static function comparison(
        Builder $query,
        string $column,
        mixed $value,
        ComparisonFilterMode $mode,
    ): void {
        $operator = match ($mode) {
            ComparisonFilterMode::GreaterThan        => '>',
            ComparisonFilterMode::GreaterThanOrEqual => '>=',
            ComparisonFilterMode::LessThan           => '<',
            ComparisonFilterMode::LessThanOrEqual    => '<=',
            ComparisonFilterMode::Equal              => '=',
        };

        $query->where($column, $operator, $value);
    }

    public static function relationship(
        Builder $query,
        string $relation,
        string $column,
        int|string $value,
        EqualityFilterMode $mode,
    ): void {
        match ($mode) {
            EqualityFilterMode::Equal => $query->whereHas(
                $relation,
                fn(Builder $q) => $q->where($column, $value)
            ),
            EqualityFilterMode::NotEqual => $query->whereDoesntHave(
                $relation,
                fn(Builder $q) => $q->where($column, $value)
            ),
        };
    }

    public static function relationshipList(
        Builder $query,
        string $relation,
        string $column,
        array $values,
        ListFilterMode $mode,
    ): void {
        match ($mode) {
            ListFilterMode::In => $query->whereHas(
                $relation,
                fn(Builder $q) => $q->whereIn($column, $values)
            ),
            ListFilterMode::NotIn => $query->whereDoesntHave(
                $relation,
                fn(Builder $q) => $q->whereIn($column, $values)
            ),
        };
    }
    public static function string(
        Builder $query,
        string $column,
        string $value,
        StringFilterMode $mode,
    ): void {
        match ($mode) {
            StringFilterMode::Like    => $query->where($column, 'like', "%{$value}%"),
            StringFilterMode::NotLike => $query->where($column, 'not like', "%{$value}%"),
        };
    }
}
