<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileLinkUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'link_email' => User::getAttrsTrans('email'),
            'link_password' => User::getAttrsTrans('password')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email = request('link_email');

        return [
            'link_email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(function ($query) {
                    $query->where('id', '!=', Auth::id());
                })
            ],
            'link_password' => [
                'required',
                function ($attribute, $value, $fail) use ($email) {
                    $user = User::withoutGlobalScope('account_tenant')
                                ->where('email', $email)
                                ->first();
                    if (empty($user) || !Hash::check($value, $user->password)) {
                        $fail(__('Password is invalid'));
                    }
                    return true;
                },
            ]
        ];
    }
}
