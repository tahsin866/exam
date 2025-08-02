<?php

namespace App\Providers\markaz\OldStudent;

use Illuminate\Support\ServiceProvider;
use App\Services\markaz\OldStudent\StudentSearchService;
use App\Repositories\markaz\OldStudent\StudentSearchRepository;
use App\Helpers\markaz\OldStudent\StudentSearchHelper;

class StudentSearchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentSearchRepository::class, function ($app) {
            return new StudentSearchRepository();
        });

        $this->app->bind(StudentSearchHelper::class, function ($app) {
            return new StudentSearchHelper();
        });

        $this->app->bind(StudentSearchService::class, function ($app) {
            return new StudentSearchService(
                $app->make(StudentSearchRepository::class),
                $app->make(StudentSearchHelper::class)
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
