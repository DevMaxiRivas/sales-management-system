<?php

namespace App\Enums\Filters;

enum ComparisonFilterMode: string
{
    case GreaterThan = "gt";
    case GreaterThanOrEqual = "ge";
    case LessThan = "lt";
    case LessThanOrEqual = "le";
    case Equal = "eq";
}
