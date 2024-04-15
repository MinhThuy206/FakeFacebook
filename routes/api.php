<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConservationController;
use App\Http\Controllers\FriendshipsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageInConservationController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\CheckLogin;
use App\Models\Conservation;
use App\Models\MessageInConservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware(CheckLogin::class);
Route::group(['prefix' => 'post', 'middleware' => CheckLogin::class], function ($route) {
    Route::post('/', [PostController::class, 'store']);
    Route::get('/', [PostController::class, 'filter']);
    Route::get('/user',[PostController::class,'filterPost']);
    Route::put('/{post}', [PostController::class, 'update']);
    Route::get('/{post}', [PostController::class, 'show']);
    Route::delete('/{post}', [PostController::class, 'destroy']);
});

Route::group(['prefix' => '/image', 'middleware' => CheckLogin::class], function ($route) {
    Route::get('/{image}', [ImageController::class, 'show']);
    Route::post('/', [ImageController::class, 'store']);
    Route::delete('/{image}', [ImageController::class, 'destroy']);
    Route::put('/', [ImageController::class, 'update']);
    Route::put('/avatar/{avatar}', [ImageController::class, 'storeAvt']);
    Route::put('/cover/{cover}', [ImageController::class, 'storeCover']);
    Route::get('media/avatar', [ImageController::class, 'filterAvatarImage']);
    Route::get('media/cover', [ImageController::class, 'filterCoverImage']);
});

Route::group(['prefix' => '/friend', 'middleware' => CheckLogin::class], function ($route) {
    Route::post('/add', [FriendshipsController::class, 'store']);
    Route::put('/accept/{friendships}', [FriendshipsController::class, 'acceptFriend']);
    Route::put('/reject/{friendships}', [FriendshipsController::class, 'rejectFriend']);
    Route::get('/user', [FriendshipsController::class, 'filterUser']);
    Route::get('/friend', [FriendshipsController::class, 'filterFriend']);
    Route::delete('/deleteFriend/{user}', [FriendshipsController::class, 'deleteFriend']);
    Route::delete('/delete/{user}', [FriendshipsController::class, 'deleteRequestAddFriend']);
});

Route::group(['prefix' => '/message', 'middleware' => CheckLogin::class], function ($route) {
    Route::get('/filterConservations',[ConservationController::class,'filterConservations']);
    Route::post('/sent',[MessageInConservationController::class,'store']);
    Route::get('/getMessage/{cons_id}',[MessageInConservationController::class,'getMessageInConservation']);
    Route::post('/storeGroup',[ConservationController::class,'store']);
    Route::post('/sentMessGroup',[MessageInConservationController::class,'store']);
});

Route::post('/broadcast',function (Request $request) {
    $pusher = Broadcast::driver('pusher')->getPusher();
    return $pusher->authorizeChannel(
        "chat.".auth()->id(),
        $request->request->get('socket_id')
    );
});
