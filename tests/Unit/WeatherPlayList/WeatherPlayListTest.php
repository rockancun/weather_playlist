<?php

namespace Tests\Unit\WeatherPlayList;

use PHPUnit\Framework\TestCase;
use App\Src\Spotify\SpotifyMemoryRepository;
use App\Src\Weather\StaticWeatherRepository;
use App\Src\WeatherPlayList\WeatherPlayList;

class WeatherPlayListTest extends TestCase
{
    private const LOW_LIMIT_PARTY_TEMPERATURE = 30.01;
    private const UPPER_LIMIT_POP_TEMPERATURE = 30;
    private const LOWER_LIMIT_POP_TEMPERATURE = 15.01;
    private const UPPER_LIMIT_ROCK_TEMPERATURE = 15;
    private const LOWER_LIMIT_ROCK_TEMPERATURE = 10.01;
    private const UPPER_LIMIT_CLASSICAL_TEMPERATURE = 15;

    private const LONDON_CITY = 'London';
    private const LONDON_COORDINATES = ["51.5074", "0.1278"];

    public function test_get_party_play_list_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::LOW_LIMIT_PARTY_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::PARTY_TRACK, $playList[0]);
    }

    public function test_get_pop_play_list_upper_limit_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::UPPER_LIMIT_POP_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::POP_TRACK, $playList[0]);
    }

    public function test_get_pop_play_list_lower_limit_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::LOWER_LIMIT_POP_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::POP_TRACK, $playList[0]);
    }

    public function test_get_rock_play_list_upper_limit_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::UPPER_LIMIT_ROCK_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::ROCK_TRACK, $playList[0]);
    }

    public function test_get_rock_play_list_lower_limit_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::LOWER_LIMIT_ROCK_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::ROCK_TRACK, $playList[0]);
    }

    public function test_get_classical_play_list_upper_limit_by_city(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::UPPER_LIMIT_CLASSICAL_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCity(self::LONDON_CITY);

        $this->assertEquals(SpotifyMemoryRepository::ROCK_TRACK, $playList[0]);
    }

    public function test_get_party_play_list_by_coordinates(): void
    {
        $weatherRepository = new StaticWeatherRepository(self::LOW_LIMIT_PARTY_TEMPERATURE);
        $spotifyRepository = new SpotifyMemoryRepository();

        $weatherPlayList = new WeatherPlayList($weatherRepository, $spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCoordinates(...self::LONDON_COORDINATES);

        $this->assertEquals(SpotifyMemoryRepository::PARTY_TRACK, $playList[0]);
    }
}
