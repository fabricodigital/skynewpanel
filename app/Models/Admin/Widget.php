<?php

namespace App\Models\Admin;

use App\Traits\DataTables\Admin\WidgetDataTable;
use App\Traits\Revisionable\Admin\WidgetRevisionable;
use App\Traits\Translations\Admin\WidgetTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use WidgetDataTable;
    use WidgetRevisionable;
    use WidgetTranslation;
    use SoftDeletes;

    protected $guarded = ['accounts'];
    protected $dashboard_id;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dashboards()
    {
        return $this->belongsToMany(Dashboard::class);
    }

    /**
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     *
     * @return HasMany
     */
    public function dashboardUserConfigs()
    {
        return $this->hasMany(DashboardUserConfig::class)->orderBy('widget_position', 'asc');
    }
}
