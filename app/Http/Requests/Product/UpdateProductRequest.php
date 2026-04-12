<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseModelFormRequest;

class UpdateProductRequest extends BaseModelFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $recordId = $this->route('record');
        return self::getRules(['recordId' => $recordId]);
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'bar_code' => 'required|numeric|unique:products,bar_code,' . $params['recordId'],
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'integer|min:0',
        ];
    }
}
