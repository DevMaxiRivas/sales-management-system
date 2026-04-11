<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ajustar según permisos
    }

    public function rules(): array
    {
        $productId = $this->route('record'); // Para Filament, ajustar según necesidad

        return [
            'bar_code' => 'required|string|unique:products,bar_code,' . $productId,
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'bar_code.required' => 'El código de barras es obligatorio.',
            'bar_code.unique' => 'El código de barras ya existe.',
            'name.required' => 'El nombre es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'min_stock.required' => 'El stock mínimo es obligatorio.',
            'min_stock.integer' => 'El stock mínimo debe ser un número entero.',
            'min_stock.min' => 'El stock mínimo no puede ser negativo.',
        ];
    }
}
