<?php

namespace App\Providers;

use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
