<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Madrasha\StudentRepository;
use App\Services\Madrasha\StudentService;
use App\Helpers\TranslationHelper;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
       public function register()
    {
        $this->app->singleton(StudentRepository::class, function ($app) {
            return new StudentRepository();
        });

        $this->app->singleton(TranslationHelper::class, function ($app) {
            return new TranslationHelper();
        });

        $this->app->singleton(StudentService::class, function ($app) {
            return new StudentService(
                $app->make(StudentRepository::class),
                $app->make(TranslationHelper::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
