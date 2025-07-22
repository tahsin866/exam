<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Madrasha\Markaz\MarkazAgreementService;
use App\Repositories\Madrasha\Markaz\MarkazAgreementRepository;
use App\Services\Madrasha\Markaz\FileUploadService;

class MarkazServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Repository
        $this->app->bind(MarkazAgreementRepository::class, function ($app) {
            return new MarkazAgreementRepository();
        });

        // Register File Upload Service
        $this->app->bind(FileUploadService::class, function ($app) {
            return new FileUploadService();
        });

        // Register Main Service with dependencies
        $this->app->bind(MarkazAgreementService::class, function ($app) {
            return new MarkazAgreementService(
                $app->make(MarkazAgreementRepository::class),
                $app->make(FileUploadService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
