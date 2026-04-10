<?php

namespace App\Providers;

use App\Contracts\Ocr\OcrReaderInterface;
use App\Services\Ocr\TesseractOcrReader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OcrReaderInterface::class, TesseractOcrReader::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
