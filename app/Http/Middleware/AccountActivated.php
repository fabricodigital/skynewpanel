<?php

namespace App\Http\Middleware;

use function __;
use Closure;
use Illuminate\Support\Facades\Auth;
use function redirect;

class AccountActivated
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
        if(Auth::check() && Auth::user()->state == 'activated') {
            return $next($request);
        }

        if(Auth::check()) {
            Auth::logout();
        }

        return redirect()->route('account.request-activation-link')
            ->withErrors(['activation_needed' => [__('Account is not activated. Please fill the form and follow the instructions!')]]);
    }
}
