<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseModelFormRequest;

class CreateProductRequest extends BaseModelFormRequest
{
    public function authorize(): bool
    {
        return true; // Ajustar según permisos
    }

    public function rules(): array
    {
        return self::getRules();
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'bar_code' => 'required|numeric|unique:products,bar_code',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'integer|min:0',
        ];
    }
}
