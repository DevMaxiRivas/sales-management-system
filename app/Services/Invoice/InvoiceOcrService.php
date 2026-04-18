<?php

namespace App\Services\Invoice;

use App\Contracts\Ocr\OcrReaderInterface;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\callback;

class InvoiceOcrService
{
    public function __construct(private OcrReaderInterface $ocrReader) {}

    public function extractLinesFromInvoiceImage(string $path, ?string $language = 'spa'): array
    {
        try {
            $text = $this->ocrReader->extractText($path, $language);

            // separate text by line breaks
            $lines = preg_split('/\r\n|\r|\n/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            // remove empty lines
            $data = array_values(array_filter(array_map('trim', $lines)));
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            $data = [];
        } finally {
            return $data;
        }
    }

    public function extractProductIdsFromInvoiceImage(string $path, ?string $language = 'spa', bool $ids_are_numeric = false, ?string $pattern = null): array
    {
        $data = array_map(
            fn(string $line) => $this->normalizeInvoiceLine($line),
            $this->extractLinesFromInvoiceImage($path, $language)
        );

        $data = array_map(fn(string $element) => str_replace(' ', '', $element), $data);

        if ($ids_are_numeric) {
            try {
                return
                    array_values(
                        array_filter(
                            array: array_map(
                                callback: function (string $element) use ($pattern) {
                                    // preg_grep throws an exception if a pattern with incorrect syntax is provided
                                    if (!is_null($pattern) && count(preg_grep($pattern, [$element])) == 0) {
                                        return null;
                                    }

                                    return preg_replace('/[^0-9]/', '', $element);
                                },
                                array: $data
                            ),
                            callback: fn($element) => !is_null($element)
                        )
                    );
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
                return [];
            }
        }
        return $data;
    }

    private function normalizeInvoiceLine(string $line): string
    {
        // Remove multiple spaces [\t\n\r] and trim 
        return preg_replace('/\s+/', ' ', trim($line));
    }
}
