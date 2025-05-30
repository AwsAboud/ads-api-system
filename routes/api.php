<?php

use App\Http\Controllers\Api\V1\AdController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

# Auth routes 
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');;

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-from-all-devices', [AuthController::class, 'logoutFromAllDevices']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    #Category 
    Route::apiResource('categories', CategoryController::class);
    #Ad 
    Route::post('ads/{ad}/change-status', [AdController::class, 'changeStatus'])->middleware('can:changeStatus,ad');
    Route::apiResource('ads', AdController::class)->middleware([
        'update' => 'can:update,ad',
        'destroy' => 'can:delete,ad'
    ]);
     #Ad 
    Route::apiResource('reviews', AdController::class);
 });

