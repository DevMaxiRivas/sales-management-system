<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseModelFormRequest extends FormRequest
{
    public static abstract function getRules(?array $params = null): array;
    public static function getRulesFromField(string $field, ?array $params = null): array
    {
        $rules = static::getRules($params);
        if (!isset($rules[$field])) {
            // throw new \RuntimeException("No se encontraron reglas para el campo {$field} en la validación.");
            return [];
        }

        if (is_string($rules[$field])) {
            return explode('|', $rules[$field]);
        }

        if (is_array($rules[$field])) {
            return $rules[$field];
        }

        throw new \RuntimeException("Ocurrio un error al obtener las reglas para el campo {$field} en la validación.");
    }
}
