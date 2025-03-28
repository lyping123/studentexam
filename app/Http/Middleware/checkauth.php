<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class checkauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role): Response
    {
        if(!Auth::check()){
            return redirect()->route("user.login")->withErrors("please login 1st.");
        }

        if(Auth::user()->role != $role){
            return redirect()->route("user.login")->withErrors("You are not authorized to access this page.");
        }

        return $next($request);
    }
}
