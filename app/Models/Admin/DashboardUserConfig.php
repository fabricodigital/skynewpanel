<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardUserConfig extends Model
{
    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    /**
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
