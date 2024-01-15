<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\CheckLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/profile', [AuthController::class, 'profile'])->name('profile') ->middleware(CheckLogin::class);
Route::group(['prefix'=>'post', 'middleware' => CheckLogin::class], function ($route){
    Route::post('/',[PostController::class, 'store']) -> name('post');

    Route::group(['prefix'=>'{post}/image' ], function ($route){
        Route::get('/{image}', [ImageController::class, 'show']);
        Route::post('/', [ImageController::class, 'store']);
        Route::delete('/{image}', [ImageController::class, 'destroy']);
    });
    Route::put('/{post}',[PostController::class, 'update']);
    Route::get('/{post}',[PostController::class, 'show']);
    Route::delete('/{post}',[PostController::class, 'destroy']);
    Route::get('/{post}',[PostController::class, 'show'])->name('showpost');

});


