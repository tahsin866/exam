<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Madrasha\StudentService;
use App\Repositories\Madrasha\StudentRepository;
use App\Helpers\TranslationHelper;

class OldStudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Helper bindings
        $this->app->bind(TranslationHelper::class, function ($app) {
            return new TranslationHelper();
        });

        // Repository bindings
        $this->app->bind(StudentRepository::class, function ($app) {
            return new StudentRepository();
        });

        // Service bindings
        $this->app->bind(StudentService::class, function ($app) {
            return new StudentService(
                $app->make(StudentRepository::class),
                $app->make(TranslationHelper::class)
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
