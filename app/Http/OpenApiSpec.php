<?php

namespace App\Http;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Server(
 *         url="http://localhost",
 *         description="API server"
 *     ),
 *     @OA\Info(
 *         version="1.0.0",
 *         title="API para obtener una lista de canciones de spotify.",
 *         description="Esta API permite obtener una lista de canciones de spotify en funcion de la temperatura de una localización geográfica ya sea por ciudad o por coordenadas geográficas en tiempo real.",
 *         @OA\Contact(name="Gaston Hidalgo Marquez", email="gaston.hikuri@gmail.com"),
 *         @OA\License(name="MIT", identifier="MIT")
 *     ),
 * )
 * @OA\Tag(
 *     name="playlist",
 *     description="Listas de canciones de spotify"
 * )
 */
class OpenApiSpec
{
}

/**
 * @OA\Schema(
 *     schema="ErrorModel",
 *     required={"code", "status", "message"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="error"
 *     ),
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *     )
 * )
 */
class ErrorModel
{
}

/**
 * @OA\Schema(
 *     schema="ApiTracksResponse",
 *     required={"code", "status", "message"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="success"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              type="string",
 *              example="play that funky music"
 *         ),
 *     )
 * )
 */
class ApiTracksResponse
{
}


/**
 * @OA\Schema(
 *     schema="ApiFailResponse",
 *     required={"code", "status", "message"},
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="error"
 *     ),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              type="string",
 *              ref="#/components/schemas/Errors",
 *              example="play that funky music"
 *         ),
 *     )
 * )
 */
class ApiFailResponse
{
}

/**
 * @OA\Schema(
 *     schema="Errors",

 *     @OA\Property(
 *         property="param",
 *         type="string",
 *         example="latitud no valida"
 *     )
 * )
 */
class Errors
{
}
