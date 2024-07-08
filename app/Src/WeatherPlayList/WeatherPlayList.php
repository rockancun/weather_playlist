<?php

declare(strict_types=1);

namespace App\Src\WeatherPlayList;

use App\Events\GetPlayListEvent;
use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;

final class WeatherPlayList
{
    public function __construct(
        readonly private WeatherRepository $weatherRepository,
        readonly private SpotifyRepository $spotifyRepository
    ) {
    }

    /**
     * @param string $city
     * @return array<string>
     */
    public function getPlayListByCity(string $city): array
    {
        $celciusTemperature = $this->weatherRepository->getCelsiusTemperatureByCity($city);
        $genre = $this->calculateGenre($celciusTemperature);

        $tracks = $this->spotifyRepository->searchByGenre($genre);

        $eventParams = [
            'location' => $city,
            'temperature' => $celciusTemperature,
            'genre' => $genre,
            'tracks' => json_encode($tracks)
        ];

        GetPlayListEvent::dispatch($eventParams);

        return $tracks;
    }

    /**
     * @param string $latitude
     * @param string $longitude
     * @return array<string>
     */
    public function getPlayListByCoordinates(string $latitude, string $longitude): array
    {
        $celsiusTemperature = $this->weatherRepository->getCelsiusTemperatureByCoordinates($latitude, $longitude);
        $genre = $this->calculateGenre($celsiusTemperature);

        $tracks = $this->spotifyRepository->searchByGenre($genre);

        $eventParams = [
            'location' => "$latitude, $longitude",
            'temperature' => $celsiusTemperature,
            'genre' => $genre,
            'tracks' => json_encode($tracks)
        ];

        GetPlayListEvent::dispatch($eventParams);

        return $tracks;
    }

    private function calculateGenre(float $celsiusTemperature): string
    {
        if($celsiusTemperature > 30) {
            return "party";
        }
        if($celsiusTemperature > 15) {
            return "pop";
        }
        if($celsiusTemperature > 10) {
            return "rock";
        }
        return "classical";
    }
}
