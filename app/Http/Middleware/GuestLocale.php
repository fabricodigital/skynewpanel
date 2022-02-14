<?php

namespace App\Http\Middleware;

use Closure;

class GuestLocale
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
        if(request()->cookie('guestLang')) {
            app()->setLocale($this->getLanguage(request()->cookie('guestLang')));

            return $next($request);
        }

        $guestLang = $this->getLanguage(substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, 2));

        app()->setLocale($guestLang);

        $response = $next($request);
        return $response->withCookie(cookie()->forever('guestLang', $guestLang));
    }

    /**
     * @param $lang
     * @return int|string|null
     */
    private function getLanguage($lang)
    {
        $availableLangs = config('main.available_languages');
        $guestLang = array_keys($availableLangs)[0];

        if (array_key_exists($lang, $availableLangs)) {
            $guestLang = $lang;
        }

        return $guestLang;
    }
}
