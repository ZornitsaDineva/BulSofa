<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/threads/{id}', [\App\Http\Controllers\MessengerController::class,'index']);

Route::get('/thread/{code}/{reader_id}', [\App\Http\Controllers\MessengerController::class,'getMessageThread']);

Route::post('/submitmessage',[\App\Http\Controllers\MessengerController::class,'sendMessage']);

Route::get('/unreadmessages/{user_id}', [\App\Http\Controllers\MessengerController::class,'getUnreadCount']);


