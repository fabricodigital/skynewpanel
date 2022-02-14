<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserLastActivityUpdater
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
            $now = \Carbon\Carbon::now();
            $recentlyActivity = Cache::get('last_activity_' . Auth::id());

            if(!$recentlyActivity) {
                Auth::user()->update(['last_activity' => $now]);
                Cache::remember('last_activity_' . Auth::id(), 0.5, function () {
                    return true;
                });
            }
        }
        return $next($request);
    }
}
