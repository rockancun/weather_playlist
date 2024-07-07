<?php

namespace Tests\Feature\Src\Weather;

use Tests\TestCase;
use App\Src\Weather\OpenWeatherRepository;

class OpenWeatherRepositoryTest extends TestCase
{
    public function test_call_open_weather_api_temperature_by_city_returns_a_successful_response(): void
    {
        $repository = new OpenWeatherRepository();
        $temperature = $repository->getCelsiusTemperatureByCity("Londres");
        $this->assertNotEmpty($temperature);
    }

    public function test_call_open_weather_api_temperature_by_coordinates_returns_a_successful_response(): void
    {
        $repository = new OpenWeatherRepository();
        $temperature = $repository->getCelsiusTemperatureByCoordinates("51.509865", "-0.118092");
        $this->assertNotEmpty($temperature);
    }

}
