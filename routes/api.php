<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Auth\MemberController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('blogs', BlogController::class);
    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');
    Route::post('/test-api/user/update/{user}', [MemberController::class, 'update']);

    Route::get('/user/my-product', [ProductController::class, 'myProduct']);
    Route::post('/user/product/add', [ProductController::class, 'store']);
    Route::get('/user/product/{id}', [ProductController::class, 'show']);
    Route::post('/user/product/update/{product}', [ProductController::class, 'update']);
    Route::delete('/user/product/delete/{id}', [ProductController::class, 'deleteProduct']);

    Route::post('/blog/comment/{blog}', [BlogController::class, 'comment']);
    Route::post('/blog/rate/{blog}', [BlogController::class, 'rate']);
});
