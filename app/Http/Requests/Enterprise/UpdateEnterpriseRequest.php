<?php

namespace App\Http\Requests\Enterprise;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Validation\Rule;

class UpdateEnterpriseRequest extends BaseModelFormRequest
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
            'name' => 'required|string|max:255',
            'tax_id' => ['required', 'integer', 'digits:' . config('config-app.enterprise_id_digits'), Rule::unique('enterprises', 'tax_id')->ignore($params['recordId'])],
        ];
    }
}
