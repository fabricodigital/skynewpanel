<?php

namespace App\Models\Admin;

use App\Traits\DataTables\Admin\AccountDataTable;
use App\Traits\Revisionable\Admin\AccountRevisionable;
use App\Traits\SoftDeletes\CascadeSoftDeletes;
use App\Traits\Translations\Admin\AccountTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Account extends Model implements HasMedia
{
    use AccountDataTable;
    use AccountRevisionable;
    use AccountTranslation;
    use SoftDeletes;
    use CascadeSoftDeletes;
    use HasMediaTrait;

    protected $guarded = ['widgets'];

    protected $cascadeDeletes = ['users'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'account_id')->withoutGlobalScope('account_tenant');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function widgets()
    {
        return $this->belongsToMany(Widget::class);
    }

    /**
     * @return mixed
     */
    public static function getUnselected()
    {
        return self::query()
            ->where('id', '!=', Auth::user()->account_id)
            ->get();
    }


    /**
     * @return mixed
     */
    public static function getSelectFilter()
    {
        return self::select(['id', 'name'])->get();
    }

    /**
     * @param Collection $accounts
     * @return Collection
     */
    public static function transformForSelectsFilters(Collection $accounts): Collection
    {
        foreach ($accounts as $account) {
            $account->label = $account->name;
            $account->value = $account->id;
        }

        return $accounts->sortBy("label");
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('logo');
    }

    /**
     *
     * @return HasMany
     */
    public function dashboardUserConfigs()
    {
        return $this->hasMany(DashboardUserConfig::class);
    }
}
