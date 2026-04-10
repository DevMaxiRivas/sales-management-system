<?php

namespace App\Providers;

use App\Contracts\Ocr\OcrReaderInterface;
use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductServiceInterface;
use App\Contracts\Enterprise\EnterpriseRepositoryInterface;
use App\Contracts\Enterprise\EnterpriseServiceInterface;
use App\Contracts\User\UserRepositoryInterface;
use App\Contracts\User\UserServiceInterface;
use App\Repositories\Product\EloquentProductRepository;
use App\Repositories\Enterprise\EloquentEnterpriseRepository;
use App\Repositories\User\EloquentUserRepository;
use App\Services\Ocr\TesseractOcrReader;
use App\Services\Product\ProductService;
use App\Services\Enterprise\EnterpriseService;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OcrReaderInterface::class, TesseractOcrReader::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(EnterpriseRepositoryInterface::class, EloquentEnterpriseRepository::class);
        $this->app->bind(EnterpriseServiceInterface::class, EnterpriseService::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
