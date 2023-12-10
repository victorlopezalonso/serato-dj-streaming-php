<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Serato\Infrastructure\Guzzle\SeratoApiClient;
use Src\Serato\Infrastructure\Repository\PlaylistRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PlaylistRepository::class, SeratoApiClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
