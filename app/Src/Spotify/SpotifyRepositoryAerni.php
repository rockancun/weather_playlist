<?php

declare(strict_types=1);

namespace App\Src\Spotify;

use Spotify;
use SpotifySeed;
use Illuminate\Support\Facades\Log;
use App\Src\Spotify\SpotifyRepository;

final class SpotifyRepositoryAerni implements SpotifyRepository
{
    /**
     * @param string $genre
     * @return array<string>
     */
    public function searchByGenre(string $genre): array
    {
        try {
            $seed = SpotifySeed::setGenres([$genre]);
            $trackRecommendations = Spotify::recommendations($seed)->get();
            return $this->getTracksArray($trackRecommendations);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }

    }

    /**
     * @param array<string> $trackRecommendations
     * @return array<string>
     */
    private function getTracksArray(array $trackRecommendations)
    {
        $tracksCollect = collect($trackRecommendations['tracks']);

        $response = [];

        foreach ($tracksCollect as $track) {
            if(array_key_exists('name', $track)) {
                $response[] = $track['name'];
            }
        }

        return $response;
    }
}
