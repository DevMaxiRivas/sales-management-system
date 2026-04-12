<?php

namespace App\Http\Requests\Enterprise;

use App\Http\Requests\BaseModelFormRequest;

class CreateEnterpriseRequest extends BaseModelFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::getRules();
    }

    public static function getRules(?array $params = []): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tax_id' => ['required', 'numeric', 'digits:' . config('config-app.enterprise_id_digits')],
        ];
    }
}
