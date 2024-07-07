<?php

declare(strict_types=1);

namespace App\Src\Spotify;

interface SpotifyRepository
{
    /**
     * @param string $genre
     * @return array<string>
     */
    public function searchByGenre(string $genre): array;
}
