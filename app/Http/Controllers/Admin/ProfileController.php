<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProfileLinkUserRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Models\Admin\UserLinkedProfile;
use App\Notifications\ProfileLinkRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\Role;
use function auth;
use function dd;
use function foo\func;
use function json_decode;
use function request;
use function response;
use function var_dump;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        $user = auth()->user();
        $user->load('roles');
        $roles = Role::getUserSelectOptions(true);
        $linkedProfiles = UserLinkedProfile::getLinkedProfiles();

        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0");

        return view('admin.profile.edit', ['user' => $user, 'roles' => $roles, 'linkedProfiles' => $linkedProfiles]);
    }

    /**
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();

        $user->update($data);

        $user->revisionableUpdateManyToMany($data);

        if(auth()->user()->can('assign_roles', User::class)) {
            $user->roles()->sync($data['roles']);
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', __('Profile updated successfully!'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale()
    {
        $validator = Validator::make(request()->all(), [
            'locale' => 'required'
        ]);

        if(!$validator->fails()) {
            auth()->user()->update(['locale' => request('locale')]);
        }

        return redirect()->back();
    }

    /**
     * @param User|null $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function postAvatar(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        request()->validate([
            'image' => 'required',
        ]);

        try {
            $image = request('image');
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imagePath = storage_path() . '/tmp/' . time() . '.'.'png';

            if (!file_exists(storage_path() . '/tmp')) {
                mkdir(storage_path() . '/tmp', 0775, true);
            }

            file_put_contents($imagePath, base64_decode($image));
        }catch (Exception $exception) {
            return response()->json(['error' => true, 500]);
        }

        $user->clearMediaCollection('profile-image');
        $user->addMedia($imagePath)->toMediaCollection('profile-image');

        if(request()->has('image')) {
            return response()->json(['success' => $user->getMedia('profile-image')[0]->getFullUrl(), 201]);
        }else {
            return response()->json(['success' => 'no file', 201]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDashboardOrder()
    {
        request()->validate([
            'widgets' => 'required',
        ]);

        auth()->user()->update([
            'settings->dashboard->widgets' => request('widgets'),
        ]);

        return response()->json(['success' => true, 201]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function postStoreSettings()
    {
        request()->validate([
            'settings_key' => 'required',
            'settings_data' => 'required',
        ]);
        $data = request()->get('settings_data');

        foreach ($data as $key => $val) {
            $data[$key] = json_decode($val, true);
        }

        $key = 'settings->' . request()->get('settings_key');

        auth()->user()->update([
            $key => $data,
        ]);

        return response()->json(['success' => true], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function postGetSettings()
    {
        request()->validate([
            'settings_key' => 'required',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $user = request()->get('user_id')
            ? User::find(request()->get('user_id'))
            : auth()->user();

        $this->authorize('get_settings', $user);

        $key = request()->get('settings_key');
        $settings = isset($user->settings[$key])
            ? $user->settings[$key]
            : [];

        return response()->json(['success' => true, 'settings' => $settings], 200);
    }

    /**
     * @param ProfileLinkUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function linkProfile(ProfileLinkUserRequest $request)
    {
        $this->authorize('link_profiles', User::class);

        $data = $request->validated();
        $user = User::withoutGlobalScope('account_tenant')
                    ->where('email', $data['link_email'])
                    ->first();

        $linkedProfile = UserLinkedProfile::getLinkedProfile($user->id);;

        if (empty($linkedProfile)) {
            $linkedProfile = UserLinkedProfile::create([
                'user_id' => Auth::id(),
                'linked_user_id' => $user->id,
                'hash' => sha1(microtime(true)),
                'hash_expired_at' => Carbon::now()->addHours(2),
                'active' => 0,
            ]);
        } elseif (!empty($linkedProfile) && !$linkedProfile->active) {
            $linkedProfile->hash = sha1(microtime(true));
            $linkedProfile->hash_expired_at = Carbon::now()->addHours(2);
            $linkedProfile->save();
        } else {
            return redirect()->route('admin.profile.edit')
                ->with('success', __('Profile is already linked.'));
        }

        $user->notify(new ProfileLinkRequest($linkedProfile->hash));

        return redirect()->route('admin.profile.edit')
            ->with('success', __('Profile link request sent successfully!'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroyLinkedProfile()
    {
        $this->authorize('link_profiles', User::class);

        $linkedProfile = UserLinkedProfile::getLinkedProfile(request('linked_user_id'));

        $linkedProfile->forceDelete();

        return redirect()->route('admin.profile.edit')
            ->with('success', __('Linked profile was deleted successfully!'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function switchLinkedProfile()
    {
        $this->authorize('link_profiles', User::class);

        $user = User::withoutGlobalScope('account_tenant')
                    ->find(request('linked_user_id'));

        $linkedProfile = UserLinkedProfile::getLinkedProfile($user->id);
        if (!empty($linkedProfile) && $linkedProfile->active) {
            auth()->logout();
            auth()->login($user);
        }

        return redirect()->route('admin.home');
    }
}
