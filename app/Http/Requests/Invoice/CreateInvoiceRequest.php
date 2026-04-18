<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateInvoiceRequest extends BaseModelFormRequest
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
        return [
            //
        ];
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'enterprise_id' => [
                'required',
                'integer',
                'exists:enterprises,id'
            ],
            'invoice_number' => [
                'sometimes',
                'nullable',
                'numeric',
                Rule::unique('invoices', 'invoice_number')->where('enterprise_id', $params['enterprise_id'])->withoutTrashed()
            ],
            'paid_at' => ['required', 'date'],
            'products.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->withoutTrashed()
            ],
            'products.*.quantity' => ['required', 'integer', 'min:0'],
            'products.*.bundles_quantity' => ['required', 'integer', 'min:0'],
            'products.*.unit_price' => ['required', 'decimal:2', 'gt:0'],
            'observations' => ['nullable', 'string'],
        ];
    }
}
