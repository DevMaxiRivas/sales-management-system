<?php

namespace App\Services\Ocr;

use App\Contracts\Ocr\OcrReaderInterface;
use RuntimeException;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOcrReader implements OcrReaderInterface
{
    public function extractText(string $path, ?string $language = null): string
    {
        if (! file_exists($path)) {
            throw new RuntimeException("OCR source file does not exist: {$path}");
        }

        $reader = new TesseractOCR($path);

        if ($language) {
            $reader->lang($language);
        }

        return trim($reader->run());
    }
}
