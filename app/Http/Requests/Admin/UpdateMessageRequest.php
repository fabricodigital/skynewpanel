<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\MessengerMessage;
use App\Rules\EditorRequired;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
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
        return MessengerMessage::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'  => new EditorRequired(),
            'attachments.*' => 'nullable|file'
        ];
    }
}
