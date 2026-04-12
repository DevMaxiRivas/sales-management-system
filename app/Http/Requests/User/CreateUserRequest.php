<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseModelFormRequest;

class CreateUserRequest extends BaseModelFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::getRules();
    }

    public static function getRules(?array $params = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
