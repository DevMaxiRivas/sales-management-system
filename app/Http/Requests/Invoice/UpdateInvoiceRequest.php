<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends BaseModelFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return self::getRules($this->route()->parameters());
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->withoutTrashed()
            ],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.unit_price' => ['required', 'numeric', 'gt:0'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
