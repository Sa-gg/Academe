<?php

namespace App\Providers;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
