<?php

namespace App\Providers;

use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\EnrollmentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
