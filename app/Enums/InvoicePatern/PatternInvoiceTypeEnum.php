<?php

namespace App\Enums\InvoicePatern;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PatternInvoiceTypeEnum: int implements HasLabel, HasColor
{
    case ProductLine = 0;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ProductLine => 'Product Line',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ProductLine => 'success',
        };
    }
}
