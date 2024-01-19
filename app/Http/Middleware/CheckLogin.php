<?php

namespace App\Http\Middleware;

use Closure;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
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
//        var_dump(request() -> cookie());
//        die('1');
//        die( var_dump($request -> hasCookie('remember')));

        if(auth() -> user() == null){
            if(($request -> hasCookie('remember'))){
                $user = \App\Models\User::query() -> where('remember_token', $request -> cookie('remember')) -> firstOrFail();
//                Auth::viaRemember();
                Auth::loginUsingId($user -> id);
                return $next($request);
            }
            return response() -> redirectToRoute('formlogin');
        }
        return $next($request);
    }
}
