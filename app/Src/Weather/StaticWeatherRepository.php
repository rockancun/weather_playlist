<?php

declare(strict_types=1);

namespace App\Src\Weather;

use App\Src\Weather\WeatherRepository;

final class StaticWeatherRepository implements WeatherRepository
{
    public function __construct(private float $staticCelsiusTemperature)
    {
    }

    public function getCelsiusTemperatureByCity(string $city): float
    {
        return $this->staticCelsiusTemperature;
    }

    public function getCelsiusTemperatureByCoordinates(string $latitude, string $longitude): float
    {
        return $this->staticCelsiusTemperature;
    }
}
