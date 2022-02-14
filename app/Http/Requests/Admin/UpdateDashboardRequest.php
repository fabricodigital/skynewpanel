<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Dashboard;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDashboardRequest extends FormRequest
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
        return Dashboard::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:25',
            'role_id' => 'required|exists:roles,id',
            'account_id' => 'required|nullable',
            'description' => 'required|min:115|max:230',
            'dashboard_image' => 'nullable|image',
        ];
    }
}
