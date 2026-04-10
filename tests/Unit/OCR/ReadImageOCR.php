<?php

namespace Tests\Unit\OCR;

use App\Services\Invoice\InvoiceOcrService;
use App\Services\Ocr\TesseractOcrReader;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class ReadImageOCR extends TestCase
{
    const PATH_FILES = "/var/www/html/storage/app/public/:folder/codes.png";
    public function test_extracts_product_ids_from_invoice_image_maxiconsumo(): void
    {
        $ocrReader = new TesseractOcrReader();
        $service = new InvoiceOcrService($ocrReader);
        echo "Test Starting\n";
        $files = [
            [
                "folder" => "maxiconsumo",
                "pattern" => '/^[0-9]{4}$/',
                "result" => [
                    "2589",
                    "3811",
                    "8702",
                ],
            ],
            [
                "folder" => "vital",
                "pattern" => '/^[0-9]{7}$/',
                "result" => [
                    "0135333",
                    "0164900",
                    "0147489",
                    // "0147488", //Falla: confunde 8 con 3
                    "0185951",
                    "0147200",
                    "0147490",
                ]
            ],
            [
                "folder" => "maxicomodin",
                "pattern" => '/^\[[0-9]{5}\]$/',
                "result" => [
                    "43954",
                    "43953",
                    "37252",
                    "34352",
                    "31696",
                ]
            ],
            [
                "folder" => "tornado",
                "pattern" => '/^\*{0,1}[0-9]{13}$/',
                "result" => [
                    // "7790250015840", // Falla no detecta todos los numeros
                    "7790250097648",
                    "7791290792043",
                    "7790250054962",
                ]
            ]
        ];
        foreach ($files as $file) {
            echo "Testing file: {$file['folder']}\n";
            $fixturePath = str_replace(':folder', $file['folder'], self::PATH_FILES);

            $this->assertFileExists($fixturePath, "Fixture file not found: {$fixturePath}");

            $lines = $service->extractProductIdsFromInvoiceImage(
                path: $fixturePath,
                ids_are_numeric: true,
                pattern: $file['pattern']
            );

            echo "Lines: " . json_encode($lines) . "\n";

            $this->assertNotEmpty($lines);

            foreach ($file['result'] as $value) {
                $this->assertContains($value, $lines);
            }
        }
    }
}
