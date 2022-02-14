<?php

namespace App\Rules\AccountTenant;

use Illuminate\Contracts\Validation\Rule;

class Exist implements Rule
{
    /**
     * @var
     */
    protected $model;

    /**
     * @var
     */
    protected $column;

    /**
     * Unique constructor.
     * @param $model
     * @param $column
     */
    public function __construct($model, $column)
    {
        $this->model = $model;
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $exist = $this->model::where($this->column, $value)->count();
        if ($exist) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exist');
    }
}
