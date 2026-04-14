<?php

namespace App\Http\Requests\Enterprise;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachProductsRequest extends BaseModelFormRequest
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
        $params = $this->route()->parameters();
        return self::getRules($params);
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'products.*.product_enterprise_id' => [
                'required',
                'integer',
                Rule::unique('product_enterprise', 'product_enterprise_id')
                    ->where(fn(Builder $query) => $query->where('enterprise_id', $params['enterprise_id']))
            ]
        ];
    }
}
