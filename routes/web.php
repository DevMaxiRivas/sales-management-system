<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

use function PHPUnit\Framework\matches;

function parse_text($text)
{

    $data = explode("\n", $text);

    foreach ($data as $item) {
        if ($item == ' ') {
            unset($data[array_search($item, $data)]);
            continue;
        }

        # Make pattern regex for prices
        $pattern = '/^\$[0-9]{1,3}(\.[0-9]{1,2})?$/';
    }

    return $data;
}

Route::get('/', function () {
    return view('welcome');
});

function us_price_with_preg_match(string $price): string
{
    // Patrón: captura parte entera (con posibles comas) y parte decimal opcional
    if (preg_match('/^([\d,]+)(?:\.(\d+))?$/', $price, $matches)) {
        $entero = preg_replace('/,/', '', $matches[1]); // eliminar comas
        $decimal = $matches[2] ?? '';

        dd($entero, $decimal);
        return $decimal === '' ? $entero : $entero . '.' . $decimal;
    }

    return $price; // fallback
}

function es_price_with_preg_match(string $price): ?float
{
    if (preg_match('/\d+(?:[.,]\d+)*/', $price, $matches)) {
        $numWithoutDecimal = preg_replace('/[^0-9]/', '', $matches[0]);

        preg_match('/[\d]+(\d{2})/', $numWithoutDecimal, $matches2);
        $decimal = $matches2[1] ?? '';
        $lenDecimal = strlen($decimal);

        return floatval(substr($numWithoutDecimal, 0, -$lenDecimal) . '.' . $decimal);
    }

    return null;
}

function normalize_price(string $price): string
{
    // Si tiene dos comas o más y un punto, probablemente US
    if (substr_count($price, ',') >= 2 && strpos($price, '.') !== false) {
        return us_price_with_preg_match($price);
    }
    // Si tiene dos puntos o más y una coma, probablemente ES
    if (substr_count($price, '.') >= 2 && strpos($price, ',') !== false) {
        return es_price_with_preg_match($price);
    }
    // Si solo tiene una coma y ningún punto, puede ser ES sin miles (ej. "1234,56")
    if (substr_count($price, ',') === 1 && strpos($price, '.') === false) {
        return str_replace(',', '.', $price);
    }
    // Si solo tiene un punto y ninguna coma, puede ser US sin miles (ej. "1234.56")
    if (substr_count($price, '.') === 1 && strpos($price, ',') === false) {
        return $price;
    }
    // Fallback: eliminar todo excepto dígitos y el último punto/coma
    return preg_replace('/[^\d]+|,(?=.*,)/', '', str_replace('.', ',', $price));
}

Route::get('/test-regex', function (Request $request) {
    $texto = $request->input('data');
    // return response()->json(['validate' => preg_match('/^\${0,1}[0-9]{1,3}(\.[0-9]{1,3})*(\,[0-9]{1,2})?$/i', $data, $matches) ? $matches[0] : "Sin Coincidencias"]);
    // return response()->json(['validate' => preg_match('/^\${0,1}[0-9]{1,}(\,[0-9]{1,2})?$/i', $data, $matches) ? $matches[0] : "Sin Coincidencias"]);

    $valdate = $request->input('encoded') == 'es' ? es_price_with_preg_match($texto) : us_price_with_preg_match($texto);
    return response()->json(['validate' => $valdate]);
});


Route::get('/ocr-test', function () {

    $files = Illuminate\Support\Facades\Storage::disk('public')->allFiles();
    $data = [];
    foreach ($files as $file) {
        $path = Storage::disk('public')->path($file);
        // dd($path);
        if (pathinfo($file, PATHINFO_EXTENSION) != 'png') continue;
        $text = (new TesseractOCR($path))
            ->lang('spa') // Specify languages if needed
            ->run();
        $parse_text = parse_text($text);
        array_push(
            $data,
            [
                "file" => $file,
                "data" => $parse_text
            ]
        );
    }
    dd($data);

    return response()->json(['extracted_text' => $text]);
});
