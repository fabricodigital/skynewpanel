<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            \Carbon\Carbon::setLocale(Auth::user()->locale);
            \App::setLocale(Auth::user()->locale);
        }
        return $next($request);
    }
}
