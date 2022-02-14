<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\CRUD_filename;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCRUD_filenameRequest extends FormRequest
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
        return CRUD_filename::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'CRUD_column_name' => 'required|string|max:191'
        ];
    }
}
