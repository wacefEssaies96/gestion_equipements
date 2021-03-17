<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        if(Auth::user()->role == 'ADMIN')
        {
            return $next($request);
        }
        if(Auth::user()->role == 'HOTLINE'){
            return redirect('/historiques');
        }
        if(Auth::user()->role == 'TECHNICIEN'){
            return redirect('/historiques');
        }
        else
        {
            return redirect('/home');
        }
    }
}
