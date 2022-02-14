<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $attrs = Role::getAttrsTrans();
        $attrs['role_name'] = Role::getAttrsTrans('name');

        return $attrs;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'role_name' => 'required|unique:roles,name,' . $this->role->id,
            'level' => 'required|integer|gte:0'
        ];

        $subRoleIds = Role::getSubLevelRoleIds(request('level'));

        $rules['sub_roles'] = 'array|in:' . implode(',', $subRoleIds);

        return $rules;
    }
}
