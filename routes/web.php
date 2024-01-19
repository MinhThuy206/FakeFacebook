<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckLogin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('page.home');
});

Route::get('/login', [AuthController::class, 'formLogin'])->name('formlogin') ;

Route::get('/register', [AuthController::class, 'formRegister'])->name('formregister');

Route::get('/post', [PostController::class, 'create'])->name('formpost') -> middleware(CheckLogin::class);

Route::get('/', [PostController::class, 'index'])-> middleware(CheckLogin::class);

Route::get('/', [AuthController::class, 'logout'])->name('logout') -> middleware(CheckLogin::class) ;



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
