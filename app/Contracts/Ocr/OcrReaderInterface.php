<?php

namespace App\Contracts\Ocr;

interface OcrReaderInterface
{
    public function extractText(string $path, ?string $language = null): string;
}
