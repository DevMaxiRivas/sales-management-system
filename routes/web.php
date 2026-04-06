<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

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

Route::get('/test-regex', function (Request $request) {
    $data = $request->input('data');
    // return response()->json(['validate' => preg_match('/^\${0,1}[0-9]{1,3}(\.[0-9]{1,3})*(\,[0-9]{1,2})?$/i', $data, $matches) ? $matches[0] : "Sin Coincidencias"]);
    return response()->json(['validate' => preg_match('/^\${0,1}[0-9]{1,}(\,[0-9]{1,2})?$/i', $data, $matches) ? $matches[0] : "Sin Coincidencias"]);
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
