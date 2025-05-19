<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user());
        // dd(auth()->user());
        //dd($request->user());

        if (!Auth::check() || Auth::user()->role !== 'user') {            
            return redirect('/');
        }
        
        //if (!auth()->check() || !auth()->user()->isAdmin()) {
        //if (!auth()->check() && !auth()->user()->hasRole('user')) {
        // if (!Auth::check() && in_array($request->user()->hasRole(), 'user')) {

        //     return redirect('/login');
        // }
        
        
        return $next($request);
    }
}
