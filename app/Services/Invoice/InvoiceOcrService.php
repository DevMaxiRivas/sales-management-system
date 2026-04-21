<?php

namespace App\Services\Invoice;

use App\Contracts\Ocr\OcrReaderInterface;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\callback;

class InvoiceOcrService
{
    public function __construct(private OcrReaderInterface $ocrReader) {}

    protected function extractLinesFromInvoiceImage(string $path, ?string $language = 'spa'): array
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

    protected function filterDataByPattern(array $data, string $pattern): array
    {
        return
            array_values(
                array_filter(
                    array: array_map(
                        callback: function (string $element) use ($pattern) {
                            // preg_grep throws an exception if a pattern with incorrect syntax is provided
                            if (count(preg_grep($pattern, [$element])) == 0) {
                                return null;
                            }

                            return preg_replace('/[^0-9]/', '', $element);
                        },
                        array: $data
                    ),
                    callback: fn($element) => !is_null($element)
                )
            );
    }

    protected function filterPricesByPattern(array $data, ?string $pattern = null): array
    {
        if (is_null($pattern)) $pattern = '/[\d]+(\d{2})/';
        return
            array_values(
                array_filter(
                    array: array_map(
                        callback: function (string $element) use ($pattern) {
                            if (preg_match('/\d+(?:[.,]\d+)*/', $element, $matches)) {
                                $numWithoutDecimal = preg_replace('/[^0-9]/', '', $matches[0]);

                                preg_match($pattern, $numWithoutDecimal, $matches2);
                                $decimal = $matches2[1] ?? '';
                                $lenDecimal = strlen($decimal);

                                return floatval(substr($numWithoutDecimal, 0, -$lenDecimal) . '.' . $decimal);
                            }

                            return null;
                        },
                        array: $data
                    ),
                    callback: fn($element) => !is_null($element)
                )
            );
    }

    protected function extractDataWithoutSpaces(string $path, ?string $language = 'spa'): array
    {
        $data = array_map(
            fn(string $line) => $this->normalizeInvoiceLine($line),
            $this->extractLinesFromInvoiceImage($path, $language)
        );

        return array_map(fn(string $element) => str_replace(' ', '', $element), $data);
    }

    public function extractProductIdsFromInvoiceImage(string $path, ?string $language = 'spa', bool $ids_are_numeric = false, ?string $pattern = null): array
    {
        $data = $this->extractDataWithoutSpaces($path, $language);
        if ($ids_are_numeric) {
            if (!is_null($pattern)) {
                try {
                    return $this->filterDataByPattern($data, $pattern);
                } catch (\Throwable $th) {
                    Log::info('Error extracting product ids from invoice image: ' . $th->getMessage());
                    return [];
                }
            } else {
                return
                    array_values(
                        array_filter(
                            array_map(
                                fn(string $element) => preg_replace('/[^0-9]/', '', $element),
                                $data
                            ),
                            fn($element) => !empty($element)
                        )
                    );
            }
        }
        return $data;
    }

    public function extractPricesFromInvoiceImage(string $path, ?string $language = 'spa', ?string $pattern = null): array
    {
        $data = $this->extractDataWithoutSpaces($path, $language);
        try {
            return $this->filterPricesByPattern($data, $pattern);
        } catch (\Throwable $th) {
            Log::info('Error extracting prices from invoice image: ' . $th->getMessage());
            return [];
        }
    }

    private function normalizeInvoiceLine(string $line): string
    {
        // Remove multiple spaces [\t\n\r] and trim 
        return preg_replace('/\s+/', ' ', trim($line));
    }
}