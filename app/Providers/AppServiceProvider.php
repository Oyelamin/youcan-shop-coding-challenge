<?php

namespace App\Providers;

use App\Exceptions\CustomApiExceptionHandler;
use App\Services\GitHubApiService;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ExceptionHandler::class,
            CustomApiExceptionHandler::class
        );
        $this->app->singleton(GitHubApiService::class, function ($app) {
            return new GitHubApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
