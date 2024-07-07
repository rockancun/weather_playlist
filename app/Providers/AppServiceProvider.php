<?php

namespace App\Providers;

use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;
use Illuminate\Support\ServiceProvider;
use App\Src\Weather\OpenWeatherRepository;
use App\Src\Spotify\SpotifyRepositoryAerni;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WeatherRepository::class, OpenWeatherRepository::class);
        $this->app->bind(SpotifyRepository::class, SpotifyRepositoryAerni::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
