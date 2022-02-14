<?php

namespace App\Rules\AccountTenant;

use Illuminate\Contracts\Validation\Rule;

class Unique implements Rule
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
     * @var
     */
    protected $except;

    /**
     * @var
     */
    protected $idColumn;

    /**
     * Unique constructor.
     * @param $model
     * @param $column
     * @param $except
     * @param $idColumn
     */
    public function __construct($model, $column, $except = NULL, $idColumn = 'id')
    {
        $this->model = $model;
        $this->column = $column;
        $this->except = $except;
        $this->idColumn = $idColumn;
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
        $query = $this->model::where($this->column, $value);
        if (!empty($this->except)) {
            $query->where('id', '!=', $this->except);
        }
        $exist = $query->count();

        if ($exist) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
