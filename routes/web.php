<?php

use App\Events\MessageSent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FriendshipsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageInConservationController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckLogin;
use Illuminate\Http\Request;
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

Route::get('/login', [AuthController::class, 'formLogin'])->name('formlogin');

Route::get('/register', [AuthController::class, 'formRegister'])->name('formregister');

Route::get('/post', [PostController::class, 'create'])->name('formpost')->middleware(CheckLogin::class);

Route::get('/friends', [FriendshipsController::class, 'create'])->name('formfriend')->middleware(CheckLogin::class);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(CheckLogin::class);

Route::get("/profile/{username}", [AuthController::class,'viewProfile'])->name('profile')->middleware(CheckLogin::class);

Route::get('message/{identify}',[MessageInConservationController::class,'form_messenger']);

//Route::get('/', function () {
////    return view('chat');
//});

//Route::post('message',function (Request $request){
//    broadcast(new MessageSent(auth()->user(), $request->input('message')));
//    return $request->input('message');
//});
