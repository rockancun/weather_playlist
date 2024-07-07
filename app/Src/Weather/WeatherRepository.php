<?php

declare(strict_types=1);

namespace App\Src\Weather;

interface WeatherRepository
{
    /**
     * @param string $city
     * @return float
     */
    public function getCelsiusTemperatureByCity(string $city): float;

    /**
     * @param string $latitude
     * @param string $longitude
     * @return float
     */
    public function getCelsiusTemperatureByCoordinates(string $latitude, string $longitude): float;
}
