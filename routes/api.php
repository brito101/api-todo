<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('ping', function () {
    return ["pong" => true];
});

Route::get('/unauthenticated', function () {
    return ["error" => 'Usuário não autenticado!'];
})->name('login');

Route::post('user', [AuthController::class, 'create']);
Route::post('auth', [AuthController::class, 'auth']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('todo', ApiController::class);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
