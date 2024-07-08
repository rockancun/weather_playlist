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

class GetPlayListByCoordinatesControllerTest extends TestCase
{
    use RefreshDatabase;

    private const UPPER_LIMIT_POP_TEMPERATURE = 30;
    private const ROUTE_NAME = 'v1.playlist.getByCoordinates';
    private const LONDON_COORDINATE_LATITUDE = "51.5074";
    private const LONDON_COORDINATE_LONGITUDE = "0.1278";

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

        $url = route(
            self::ROUTE_NAME,
            [
                'latitude' => self::LONDON_COORDINATE_LATITUDE,
                'longitude' => self::LONDON_COORDINATE_LONGITUDE
            ]
        );

        $response = $this->get($url);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            "status" => "success",
            "data" => [SpotifyMemoryRepository::POP_TRACK]
        ]);

        $this->assertDatabaseHas('play_list_statistics', [
            'location' => self::LONDON_COORDINATE_LATITUDE . ', ' . self::LONDON_COORDINATE_LONGITUDE,
            'temperature' => self::UPPER_LIMIT_POP_TEMPERATURE,
            'genre' => 'pop',
            'tracks' => json_encode([SpotifyMemoryRepository::POP_TRACK])
        ]);


    }

    public function test_get_pop_play_list_invalid_request_fail(): void
    {
        $this->app->instance(
            WeatherRepository::class,
            new StaticWeatherRepository(self::UPPER_LIMIT_POP_TEMPERATURE)
        );

        $response = $this->get(
            route(self::ROUTE_NAME, [
                'latitude' => "-91.0",
                'longitude' => self::LONDON_COORDINATE_LONGITUDE
            ])
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "status" => "fail",
            "data" => [
                "latitude" => ["The latitude field must be between -90 and 90."]
            ]
        ]);
    }



    public function test_get_pop_play_list_weather_exception_error(): void
    {
        $mock = Mockery::mock(WeatherRepository::class);
        $mock->shouldReceive("getCelsiusTemperatureByCoordinates")->andReturnUsing(function () {
            throw new WeatherException();
        });

        $this->app->instance(WeatherRepository::class, $mock);

        $response = $this->get(
            route(self::ROUTE_NAME, [
                'latitude' => self::LONDON_COORDINATE_LATITUDE,
                'longitude' => self::LONDON_COORDINATE_LONGITUDE
            ])
        );

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE);
        $response->assertJson(
            [
                "status" => "error",
                "message" => "Servicio de clima no disponible"
            ],
        );
    }

    public function test_get_pop_play_list_exception_error(): void
    {
        $mock = Mockery::mock(WeatherRepository::class);
        $mock->shouldReceive("getCelsiusTemperatureByCoordinates")->andReturnUsing(function () {
            throw new \Exception();
        });

        $this->app->instance(WeatherRepository::class, $mock);

        $response = $this->get(
            route(self::ROUTE_NAME, [
                'latitude' => self::LONDON_COORDINATE_LATITUDE,
                'longitude' => self::LONDON_COORDINATE_LONGITUDE
            ])
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
