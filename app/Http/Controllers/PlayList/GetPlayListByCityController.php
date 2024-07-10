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
use App\Src\Weather\WeatherCityNotFoundException;
use App\Src\Weather\WeatherRepository;
use App\Src\WeatherPlayList\WeatherPlayList;

class GetPlayListByCityController extends Controller
{
    public function __construct(
        readonly private WeatherRepository $weatherRepository,
        readonly private SpotifyRepository $spotifyRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/playlist/getByCity/{city}",
     *     description="Regresa una lista de canciones de spotify por ciudad",
     *     operationId="getByCity",
     *     tags={"playlist"},
     *     @OA\Parameter(
     *         name="city",
     *         in="path",
     *         description="Regresa una lista de canciones de spotify por ciudad",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="London"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Operación exitosa devuelve la lista de canciones",
     *         @OA\JsonContent(ref="#/components/schemas/ApiTracksResponse"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Recurso no encontrado devuelve mensaje de error 'No se encuentra una ciudad con ese nombre'",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Operación fallida devuelve lista de mensajes de error de validación de los parámetros enviados",
     *         @OA\JsonContent(ref="#/components/schemas/ApiFailResponse"),
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Operación fallida devuelve mensaje de error 'Servicio no disponible contacte al administrador'",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response="503",
     *         description="Operación fallida devuelve mensaje de error 'Servicio de clima no disponible'",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel"),
     *     )
     * )
     */
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
        } catch(WeatherCityNotFoundException $exception) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "No se encuentra una ciudad con ese nombre"
                ],
                Response::HTTP_NOT_FOUND
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
