<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('auth.login');
    }

    public function login (LoginRequest $request)
    {
        if(auth() -> attempt([
            'email' => $request -> email,
            'password' => $request -> password
        ], $request -> remember ?? false)){
            return response() -> json(['message'=>'login success'])->withCookie(cookie('remember',\auth() ->user()->getRememberToken()));
        }else{
            return response() -> json(['message'=>'login fail'], 403);
        }
    }

    public function formRegister()
    {
        return view('auth.register');
    }

    public function register(RegistrationRequest $request)
    {
        $user = User::query()->create([
            'email' => $request -> email,
            'name' => $request -> name,
            'password' => $request -> password,
            'phone' => $request -> phone
        ]);
        return response() -> json(['message'=>'Registration success']);
    }

    function profile()
    {
        $user = User::query() -> where('id', Auth::id()) ->first();
        return response() -> json(['name' => $user -> name, 'email' => $user -> email, 'phone' => $user -> phone]);
    }
}
