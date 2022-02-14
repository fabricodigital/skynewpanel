<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin\Event;
use App\Models\Admin\User;
use App\Rules\AccountTenant\Exist;
use function __;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreEventRequest extends FormRequest
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
        return Event::getAttrsTrans();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'start' => [
                'required',
                function($attribute, $value, $fail) {
                    if(Carbon::createFromFormat('d/m/Y H:i', $value) == false) {
                        return $fail(':attribute is invalid date.');
                    }
                    return true;
                }
            ],
            'end' => [
                'required',
                function($attribute, $value, $fail) {
                    if(Carbon::createFromFormat('d/m/Y H:i', $value) == false) {
                        return $fail(':attribute is invalid date.');
                    }
                    return true;
                },
                function($attribute, $value, $fail) {
                    $endDate = Carbon::createFromFormat('d/m/Y H:i', $value);
                    $startDate = Carbon::createFromFormat('d/m/Y H:i', request('start'));
                    if(!$endDate->gt($startDate)) {
                        return $fail(__(':attribute end should be after start'));
                    }
                    return true;
                }
            ],
            'roles' => 'nullable|exists:roles,id',
            'users' => [
                'nullable',
                new Exist(User::class, 'id')
            ],
            'color' => 'required',
            'description' => 'nullable',
        ];
    }
}
