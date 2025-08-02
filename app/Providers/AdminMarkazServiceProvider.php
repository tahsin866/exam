<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\admin\markaz\AdminMarkazRepositoryInterface;
use App\Repositories\admin\markaz\AdminMarkazRepository;

class AdminMarkazServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AdminMarkazRepositoryInterface::class,
            AdminMarkazRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
