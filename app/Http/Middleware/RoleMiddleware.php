<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $role): Response
    {
        if (!Auth::check()) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route("user.login")->withErrors("please login 1st.");
        }
        
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized action.'); // 403 if role doesn't match
        }
        
        return $next($request);
    }
}
