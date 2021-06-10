<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoomController;
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

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::get('rooms', [RoomController::class, 'index']);
Route::group(['prefix' => 'booking', 'middleware' => 'auth:sanctum'], function () {
	Route::get('/list', [BookingController::class, 'index']);
	Route::post('add', [BookingController::class, 'add']);
	Route::get('edit/{id}', [BookingController::class, 'edit']);
	Route::post('update/{id}', [BookingController::class, 'update']);
	Route::delete('delete/{id}', [BookingController::class, 'delete']);
});
