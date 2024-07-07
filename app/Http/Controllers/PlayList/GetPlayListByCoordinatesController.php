<?php

namespace App\Http\Controllers\PlayList;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;
use App\Src\WeatherPlayList\WeatherPlayList;

use Illuminate\Http\Request;

class GetPlayListByCoordinatesController extends Controller
{
    public function __construct(
        readonly private WeatherRepository $weatherRepository,
        readonly private SpotifyRepository $spotifyRepository
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $weatherPlayList = new WeatherPlayList($this->weatherRepository, $this->spotifyRepository);
        $playList = $weatherPlayList->getPlayListByCoordinates($request->latitude, $request->longitude);

        return response()->json(
            [
                "status" => "success",
                "data" => $playList
            ]
        );
    }

}
