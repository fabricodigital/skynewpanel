<?php

namespace App\Http\Controllers;

class GuestController extends Controller
{
    /**
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getGuestLocale(string $locale)
    {
        return redirect()
            ->back()
            ->withCookie(cookie()->forever('guestLang', $locale));
    }
}
