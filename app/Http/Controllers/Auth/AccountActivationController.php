<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin\User;
use App\Models\Admin\UserLinkedProfile;
use App\Notifications\Auth\AccountActivationLink;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class AccountActivationController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requestActivationLink()
    {
        return view('auth.accounts.activation');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendActivationLink()
    {
        $this->validate(\request(), [
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) {
                    $query->where('email', request('email'))
                        ->whereNull('deleted_at');
                })
            ]
        ]);

        $user = User::where('email', \request('email'))->first();

        $user->notify( new AccountActivationLink() );

        return redirect()->route('login')
            ->with(['success' => __("To complete your account activation, please click the dedicated button inside the verification email we've just sent to you.")]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activationForm()
    {
        $this->validateUser();

        return view('auth.accounts.reset', ['token' => request('token')]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function activate()
    {
        $user = $this->validateUser();

        $this->validate(request(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($user->state == 'activated') {
            return redirect()->route('login')
                ->withErrors(['account_already_activated' => [__('Account is already active! Please login')]]);
        }

        $user->update([
            'password' => request('password'),
            'state' => 'activated',
        ]);

        Auth::login($user);

        return redirect()->route('admin.home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateLinkedProfiles()
    {
        $user = $this->validateUser();
        $redirectRoute = Auth::id() ? 'admin.profile.edit' : 'login';

        try {
            $hash = Crypt::decrypt(request('hash'));
        }catch (\Exception $exception) {
            abort(404);
        }

        $linkedProfile = UserLinkedProfile::where(function ($query) use ($user){
                                $query->where('user_id', $user->id)
                                    ->orWhere('linked_user_id', $user->id);
                            })
                            ->where('hash', $hash)
                            ->first();
        if (empty($linkedProfile)) {
            abort(404);
        }

        if (!$linkedProfile->active) {
            if ($linkedProfile->hash_expired_at->lte(Carbon::now())) {
                return redirect()->route($redirectRoute)
                    ->withErrors(['link_hash_expired' => [__('Profile link request link has been expired.')]]);
            }

            $linkedProfile->hash = null;
            $linkedProfile->hash_expired_at = null;
            $linkedProfile->active = true;
            $linkedProfile->save();

            return redirect()->route($redirectRoute)
                ->with('success', __("The profiles are linked successfully!"));
        } else {
            return redirect()->route($redirectRoute)
                ->with('success', __("The profiles are already linked!"));
        }
    }

    /**
     * @return mixed
     */
    private function validateUser()
    {
        try {
            $token = Crypt::decrypt(request('token'));
        }catch (\Exception $exception) {
            abort(404);
        }

        $user = User::withoutGlobalScope('account_tenant')
                    ->where('email', $token)
                    ->firstOrFail();

        return $user;
    }
}
