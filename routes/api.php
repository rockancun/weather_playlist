<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayList\GetPlayListByCityController;
use App\Http\Controllers\PlayList\GetPlayListByCoordinatesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v1/playlist/getByCity/{city}', GetPlayListByCityController::class)
    ->name("v1.playlist.getByCity");

Route::get('/v1/playlist/getByCoordinates/{latitude}/{longitude}', GetPlayListByCoordinatesController::class)
    ->name("v1.playlist.getByCoordinates");
