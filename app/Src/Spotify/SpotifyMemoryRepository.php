<?php

declare(strict_types=1);

namespace App\Src\Spotify;

use App\Src\Spotify\SpotifyRepository;

final class SpotifyMemoryRepository implements SpotifyRepository
{
    public const PARTY_TRACK = "Party music track 1";
    public const POP_TRACK = "Pop music track 1";
    public const ROCK_TRACK = "Rock music track 1";
    public const CLASSICAL_TRACK = "Classical music track 1";

    /**
     * @param string $genre
     * @return array<string>
     */
    public function searchByGenre(string $genre): array
    {
        switch ($genre) {
            case "party":
                return [self::PARTY_TRACK];
            case "pop":
                return [self::POP_TRACK];
            case "rock":
                return [self::ROCK_TRACK];
            case "classical":
                return [self::CLASSICAL_TRACK];
            default:
                return [];
        }
    }

}
