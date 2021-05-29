<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RegisterController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\User\RoleController;

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

Route::post('login', [RegisterController::class, 'login']);
     
Route::middleware('auth:sanctum')->group( function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::resource('users', RegisterController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('roles', RoleController::class);
});

