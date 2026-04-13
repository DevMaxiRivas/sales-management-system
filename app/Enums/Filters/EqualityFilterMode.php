<?php

namespace App\Enums\Filters;

enum EqualityFilterMode: string
{
    case Equal = 'eq';
    case NotEqual = 'ne';
}
