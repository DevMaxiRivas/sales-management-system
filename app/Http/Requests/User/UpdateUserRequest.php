<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseModelFormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends BaseModelFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('record'))],
            'password' => 'nullable|string|min:8',
        ];
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($params['recordId'])],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}
