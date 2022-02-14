<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\MessengerMessage;
use App\Rules\EditorRequired;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Admin\Role;
use App\Models\Admin\User;

class StoreMessageRequest extends FormRequest
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
        $rules = array(
            'subject'  => 'required',
            'content'  => new EditorRequired(),
            'type' => [
                'required',
                Rule::in(['direct', 'help']),
            ],
            'receiver_model' => [
                'required',
                Rule::in([User::class, Role::class]),
            ],
            'attachments.*' => 'nullable|file'
        );
        if(request('type') === 'help') {
            $specific_rules = array(
                'receiver' => 'required|integer|exists:roles,id',
            );
        }else{
            $specific_rules = array(
                'receiver' => 'required|integer|exists:users,id',
            );
        }

        return array_merge($rules, $specific_rules);
    }
}
