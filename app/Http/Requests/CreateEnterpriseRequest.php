<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEnterpriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajustar según permisos
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tax_id' => 'required|integer|digits:' . config('config-app.enterprise_id_digits'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'tax_id.required' => 'El ID fiscal es obligatorio.',
            'tax_id.integer' => 'El ID fiscal debe ser un número entero.',
            'tax_id.digits' => 'El ID fiscal debe tener ' . config('config-app.enterprise_id_digits') . ' dígitos.',
        ];
    }
}
