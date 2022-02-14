<?php

namespace App\Models\Admin;

use App\Traits\DataTables\Admin\DashboardDataTable;
use App\Traits\Revisionable\Admin\DashboardRevisionable;
use App\Traits\Translations\Admin\DashboardTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Dashboard extends Model implements HasMedia
{
    use DashboardDataTable;
    use DashboardRevisionable;
    use DashboardTranslation;
    use SoftDeletes;
    use HasMediaTrait;

    protected $guarded = ['dashboard_image'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function widgets()
    {
        return $this->belongsToMany(Widget::class);
    }

    /**
     *
     * @return HasMany
     */
    public function dashboardUserConfigs()
    {
        return $this->hasMany(DashboardUserConfig::class);
    }

    /**
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     *
     * @return string
     */
    public function getNameSluggedAttribute()
    {
        return Str::slug($this->name);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('dashboard_image');
    }
}
