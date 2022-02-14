<?php

namespace App\Traits\MultiTenant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait RevisionAccountTenant
{
    /**
     *
     */
    protected static function bootRevisionAccountTenant()
    {
        if (!Auth::user()) {
            return ;
        }

        $accountId = Auth::user()->account_id;

        $tableName = (new static())->getTable();

        static::addGlobalScope('account_tenant', function (Builder $builder) use ($accountId, $tableName) {
            $builder->where($tableName . '.account_id', $accountId);
            if (Auth::user()->hasRole('Super Administrator')) {
                $builder->orWhereNull($tableName . '.account_id');
            }
        });

        self::creating(function ($model) use ($accountId) {
            if (in_array('App\Traits\MultiTenant\AccountTenant', class_uses($model->model_type))) {
                $model->account_id = $accountId;
            }
        });
    }
}
