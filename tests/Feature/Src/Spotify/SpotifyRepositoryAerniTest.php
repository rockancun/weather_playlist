<?php

namespace Tests\Feature\Src\Spotify;

use Tests\TestCase;
use App\Src\Spotify\SpotifyRepositoryAerni;

class SpotifyRepositoryAerniTest extends TestCase
{
    public function test_call_spotify_api_returns_a_successful_response(): void
    {
        $repository = new SpotifyRepositoryAerni();
        $tracks = $repository->searchByGenre("rock");
        $this->assertNotEmpty($tracks);
    }
}
