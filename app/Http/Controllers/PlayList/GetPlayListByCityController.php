<?php

namespace App\Http\Controllers\PlayList;

use Error;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Src\Weather\WeatherException;
use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;
use App\Src\WeatherPlayList\WeatherPlayList;

class GetPlayListByCityController extends Controller
{
    public function __construct(
        readonly private WeatherRepository $weatherRepository,
        readonly private SpotifyRepository $spotifyRepository
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $weatherPlayList = new WeatherPlayList($this->weatherRepository, $this->spotifyRepository);
            $playList = $weatherPlayList->getPlayListByCity($request->city);

            return response()->json(
                [
                    "status" => "success",
                    "data" => $playList
                ]
            );
        } catch(WeatherException $exception) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Servicio de Clima no disponible"
                ],
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        } catch(\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Servicio no disponible contacte al administrador"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
