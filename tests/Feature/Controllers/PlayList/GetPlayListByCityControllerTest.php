<?php

namespace Tests\Feature\Controllers\PlayList;

use Mockery;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Src\Weather\WeatherException;
use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;
use App\Src\Spotify\SpotifyMemoryRepository;
use App\Src\Weather\StaticWeatherRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPlayListByCityControllerTest extends TestCase
{
    use RefreshDatabase;

    private const UPPER_LIMIT_POP_TEMPERATURE = 30;
    private const ROUTE_NAME = 'v1.playlist.getByCity';

    public function setUp(): void
    {
        parent::setUp();
        $this->app->bind(SpotifyRepository::class, SpotifyMemoryRepository::class);
    }

    public function test_get_pop_play_list_success(): void
    {
        $this->app->instance(
            WeatherRepository::class,
            new StaticWeatherRepository(self::UPPER_LIMIT_POP_TEMPERATURE)
        );

        $response = $this->get(
            route(self::ROUTE_NAME, ['city' => 'London'])
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            "status" => "success",
            "data" => [SpotifyMemoryRepository::POP_TRACK]
        ]);

        $this->assertDatabaseHas('play_list_statistics', [
            'location' => 'London',
            'temperature' => self::UPPER_LIMIT_POP_TEMPERATURE,
            'genre' => 'pop',
            'tracks' => json_encode([SpotifyMemoryRepository::POP_TRACK])
        ]);
    }

    public function test_get_pop_play_list_city_not_found_weather_exception_error(): void
    {
        $mock = Mockery::mock(WeatherRepository::class);
        $mock->shouldReceive("getCelsiusTemperatureByCity")->andReturnUsing(function () {
            throw new WeatherException();
        });

        $this->app->instance(WeatherRepository::class, $mock);

        $response = $this->get(
            route(self::ROUTE_NAME, ['city' => 'London'])
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertJson(
            [
                "status" => "error",
                "message" => "Servicio de Clima no disponible"
            ],
        );
    }

    public function test_get_pop_play_list_weather_exception_error(): void
    {
        $mock = Mockery::mock(WeatherRepository::class);
        $mock->shouldReceive("getCelsiusTemperatureByCity")->andReturnUsing(function () {
            throw new WeatherException();
        });

        $this->app->instance(WeatherRepository::class, $mock);

        $response = $this->get(
            route(self::ROUTE_NAME, ['city' => 'London'])
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertJson(
            [
                "status" => "error",
                "message" => "Servicio de Clima no disponible"
            ],
        );
    }

    public function test_get_pop_play_list_exception_error(): void
    {
        $mock = Mockery::mock(WeatherRepository::class);
        $mock->shouldReceive("getCelsiusTemperatureByCity")->andReturnUsing(function () {
            throw new \Exception();
        });

        $this->app->instance(WeatherRepository::class, $mock);

        $response = $this->get(
            route(self::ROUTE_NAME, ['city' => 'London'])
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson(
            [
                "status" => "error",
                "message" => "Servicio no disponible contacte al administrador"
            ],
        );
    }
}
