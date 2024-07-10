<?php

namespace App\Http\Controllers\PlayList;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Src\Weather\WeatherException;
use App\Src\Spotify\SpotifyRepository;
use App\Src\Weather\WeatherRepository;
use App\Src\WeatherPlayList\WeatherPlayList;
use App\Http\Requests\PlayList\GetPlayListByCoordinatesRequest;
use Illuminate\Support\Facades\Validator;

class GetPlayListByCoordinatesController extends Controller
{
    public function __construct(
        readonly private WeatherRepository $weatherRepository,
        readonly private SpotifyRepository $spotifyRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/playlist/getByCoordinates/{latitude}/{longitude}",
     *     description="Regresa una lista de canciones de spotify por coordenadas geográficas de latitud y longitud",
     *     operationId="getByCoordinates",
     *     tags={"playlist"},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="path",
     *         description="latitud geográfica",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="51.5074"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="path",
     *         description="Longitud geográfica",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="0.1278"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Operación exitosa devuelve la lista de canciones",
     *         @OA\JsonContent(ref="#/components/schemas/ApiTracksResponse"),
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
            $validationErrors = $this->getValidationErrors($request);

            if (!empty($validationErrors)) {
                return response()->json(
                    [
                        "status" => "fail",
                        "data" => $validationErrors
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            $weatherPlayList = new WeatherPlayList($this->weatherRepository, $this->spotifyRepository);

            $playList = $weatherPlayList->getPlayListByCoordinates(
                $request->latitude,
                $request->longitude
            );

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
                    "message" => "Servicio de clima no disponible"
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

    private function getValidationErrors($request)
    {
        $attributes = [
            'latitude' =>  $request->latitude,
            'longitude' => $request->longitude
    ];

        $validator = Validator::make($attributes, [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return [];
    }
}
