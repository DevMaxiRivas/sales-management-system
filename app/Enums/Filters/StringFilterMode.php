<?php

namespace App\Enums\Filters;

enum StringFilterMode: string
{
    case Like = "like";
    case NotLike = "not_like";
}
