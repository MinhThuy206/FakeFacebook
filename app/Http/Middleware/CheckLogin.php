<?php

namespace App\Http\Middleware;

use Closure;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        die( var_dump(Auth::user()));

        if(auth() -> user() == null){
            if($request -> hasCookie('remember')){
                $user = \App\Models\User::query() -> where('remember_token', $request -> cookie('remember')) -> firstOrFail();
                Auth::loginUsingId($user -> id);
                return $next($request);
            }
            return response() -> view('auth.login');
        }
        return $next($request);
    }
}
