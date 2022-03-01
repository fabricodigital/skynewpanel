<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Promotion;
use Illuminate\Foundation\Http\FormRequest;

class StorePromotionRequest extends FormRequest
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
        return Promotion::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|string|max:191',
            'abbr' => 'required|string|max:191',
            'datainizio' => 'required|date_format:d/m/Y H:i',
            'datafine' => 'required|date_format:d/m/Y H:i',
            'tipologiaskyservice' => 'required|string|max:191',
        ];
    }
}
