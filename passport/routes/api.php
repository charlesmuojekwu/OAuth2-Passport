<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('home',[AuthController::class, 'home']);

Route::post('register',[AuthController::class, 'register']);

Route::post('login',[AuthController::class, 'login']);


Route::middleware(['auth:api','scopes:get-email,get-name'])->group(function () { 

   Route::get('user',[AuthController::class, 'getUser']);

   Route::post('logout',[AuthController::class, 'logout']);

});

Route::fallback(function () {
    return response('Not found',Response::HTTP_NOT_FOUND);
});
