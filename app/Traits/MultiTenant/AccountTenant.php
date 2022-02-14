<?php

namespace App\Traits\MultiTenant;

use App\Models\Admin\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait AccountTenant
{
    /**
     *
     */
    protected static function bootAccountTenant()
    {
        if (!Auth::user()) {
            return ;
        }

        $accountId = Auth::user()->account_id;

        $tableName = (new static())->getTable();

        static::addGlobalScope('account_tenant', function (Builder $builder) use ($accountId, $tableName) {
            $builder->where($tableName . '.account_id', $accountId);
        });

        self::creating(function ($model) use ($accountId) {
            if (empty($model->account_id)) {
                $model->account_id = $accountId;
            }
        });
    }
}
