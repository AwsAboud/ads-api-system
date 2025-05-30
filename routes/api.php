<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AdController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ReviewController;
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
//     Route::middleware('can:create,App\Models\Category')->post('categories', [CategoryController::class, 'store']);
// Route::middleware('can:update,category')->put('categories/{category}', [CategoryController::class, 'update']);
// Route::middleware('can:delete,category')->delete('categories/{category}', [CategoryController::class, 'destroy']);
    // Route::apiResource('categories', CategoryController::class)
    // ->middleware([
    //     'store' => 'can:create,category',
    //     'update' => 'can:update,category',
    //     'destroy' => 'can:delete,category,'
    // ]);

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('{category}', [CategoryController::class, 'show'])->name('categories.show');

        Route::post('/', [CategoryController::class, 'store'])
            ->middleware('can:create,App\Models\Category')
            ->name('categories.store');

        Route::put('{category}', [CategoryController::class, 'update'])
            ->middleware('can:update,category')
            ->name('categories.update');

        Route::patch('{category}', [CategoryController::class, 'update'])
            ->middleware('can:update,category');

        Route::delete('{category}', [CategoryController::class, 'destroy'])
            ->middleware('can:delete,category')
            ->name('categories.destroy');
    });


    #Ad
    Route::group(['prefix' => 'ads', 'middleware' => 'auth:sanctum'], function () {
        Route::post('{ad}/change-status', [AdController::class, 'changeStatus'])
            ->middleware('can:changeStatus,ad')
            ->name('ads.change-status');

         Route::get('/active', [AdController::class, 'indexByActive'])->name('ads.index');
        Route::get('/', [AdController::class, 'index'])->name('ads.index');
        Route::get('{ad}', [AdController::class, 'show'])->name('ads.show');

        Route::post('/', [AdController::class, 'store'])->name('ads.store');

        Route::put('{ad}', [AdController::class, 'update'])
            ->middleware('can:update,ad')
            ->name('ads.update');

        Route::patch('{ad}', [AdController::class, 'update'])
            ->middleware('can:update,ad');

        Route::delete('{ad}', [AdController::class, 'destroy'])
            ->middleware('can:delete,ad')
            ->name('ads.destroy');
    });

    # Review
    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('{review}', [ReviewController::class, 'show'])->name('reviews.show');

        Route::post('/', [ReviewController::class, 'store'])
            ->middleware('can:create,App\Models\Review')
            ->name('reviews.store');

        Route::put('{review}', [ReviewController::class, 'update'])->name('reviews.update');

        Route::patch('{review}', [ReviewController::class, 'update'])
            ->middleware('can:update,review');

        Route::delete('{review}', [ReviewController::class, 'destroy'])
            ->middleware('can:delete,review')
            ->name('reviews.destroy');
    });
 });

