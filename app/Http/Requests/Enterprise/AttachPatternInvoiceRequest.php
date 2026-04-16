<?php

namespace App\Http\Requests\Enterprise;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachPatternInvoiceRequest extends BaseModelFormRequest
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
            'type' => [
                'required',
                'integer',
                isset($params['record_id']) ?
                    Rule::unique('invoice_patterns', 'type')->where('enterprise_id', $params['enterprise_id'])->withoutTrashed()->ignore($params['record_id'])
                    : Rule::unique('invoice_patterns', 'type')->where('enterprise_id', $params['enterprise_id'])->withoutTrashed()
            ],
            'pattern' => ['required', 'string'],
        ];
    }
}
