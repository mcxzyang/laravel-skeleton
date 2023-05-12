<?php

use App\Http\Controllers\Api\Mobile\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\AuthController;
use App\Http\Controllers\HookController;

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

//Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('/webhook', [HookController::class, 'webhook']);

Route::group(['prefix' => 'mobile'], function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/testLogin', [AuthController::class, 'testLogin']);

    Route::group(['middleware' => 'auth:mobile'], function () {
        Route::get('/auth/me', [UserController::class, 'me']);
        Route::put('/auth/me', [UserController::class, 'update']);
    });
});
