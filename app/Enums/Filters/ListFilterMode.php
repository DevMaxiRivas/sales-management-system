<?php

namespace App\Enums\Filters;

enum ListFilterMode: string
{
    case In = 'in';
    case NotIn = 'not_in';
}
