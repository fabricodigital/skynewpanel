<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\User;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $attributes = User::getAttrsTrans();
        $attributes['password_confirmation'] = __('password_confirmation-form-label');
        return $attributes;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:191',
            'surname' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'locale' => 'nullable|in:' . implode(',', array_keys(config('main.available_languages'))),
        ];

        if(Auth::user()->can('change_state', User::class)) {
            $rules['state'] = 'required|in:' . implode(',', array_keys(User::getEnumsTrans('state')));
        }

        if(Auth::user()->can('assign_roles', User::class)) {
            $rules ['roles']= 'required|array|in:' . implode(',', array_keys(Role::getUserSelectOptions()->toArray()));
        }

        return $rules;
    }
}
