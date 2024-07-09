<?php

declare(strict_types=1);

namespace App\Src\Weather;

use RakibDevs\Weather\Weather;
use Illuminate\Support\Facades\Log;
use App\Src\Weather\WeatherException;
use App\Src\Weather\WeatherRepository;
use App\Src\Weather\WeatherCityNotFoundException;
use RakibDevs\Weather\Exceptions\WeatherException as WeatherExceptionRakibDevs;

final class OpenWeatherRepository implements WeatherRepository
{
    public function getCelsiusTemperatureByCity(string $city): float
    {
        try {
            $openWeather = new Weather();
            $result = $openWeather->getCurrentByCity($city);
            return (float)$result->main->temp;
        } catch (WeatherExceptionRakibDevs $exception) {
            Log::error($exception->getMessage());
            throw new WeatherCityNotFoundException();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new WeatherException();
        }
    }

    public function getCelsiusTemperatureByCoordinates(string $latitude, string $longitude): float
    {
        try {
            $openWeather = new Weather();
            $result = $openWeather->getCurrentByCord($latitude, $longitude);
            return (float)$result->main->temp;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new WeatherException();
        }
    }
}
