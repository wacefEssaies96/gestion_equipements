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
        // if(Auth::user()->role != 'ADMIN')
            return redirect('/home');
            // return view('./js/components/Historiques/Hotline/Historiques');
            // return redirect('/historiques');
        
        // return $next($request);
        
        abort(403);
    }
}
