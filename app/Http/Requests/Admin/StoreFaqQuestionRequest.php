<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\FaqCategory;
use App\Models\Admin\FaqQuestion;
use App\Rules\AccountTenant\Exist;
use App\Rules\EditorRequired;
use function dd;
use Illuminate\Foundation\Http\FormRequest;
use function request;

class StoreFaqQuestionRequest extends FormRequest
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
        return FaqQuestion::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => [
                'required',
                new Exist(FaqCategory::class, 'id')
            ],
            'question_text' => new EditorRequired(),
            'answer_text' => new EditorRequired(),
            'attachments.*' => 'nullable|file'
        ];
    }
}
