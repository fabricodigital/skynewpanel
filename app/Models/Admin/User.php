<?php

namespace App\Models\Admin;

use App\Notifications\Auth\ResetPassword;
use App\Traits\DataTables\Admin\UserDataTable;
use App\Traits\MultiTenant\AccountTenant;
use App\Traits\Revisionable\Admin\UserRevisionable;
use App\Traits\SoftDeletes\CascadeSoftDeletes;
use App\Traits\Translations\Admin\UserTranslation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use HasRoles;
    use HasMediaTrait;
    use UserRevisionable;
    use UserTranslation;
    use UserDataTable;
    use AccountTenant;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $guarded = ['roles'];
    protected $dates = ['last_activity'];
    protected $casts = [
        'logged' => 'boolean',
        'read_notifications' => 'array',
        'read_events' => 'array',
        'settings' => 'array',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $cascadeDeletes = ['usersLinkedProfiles', 'usersLinkedProfilesInverse'];

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        )->select('*');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function has_not_permissions()
    {
        return $this->morphToMany(
            \App\Models\Admin\Permission::class,
            'model',
            'model_has_not_permissions',
            'model_id',
            'permission_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function impersonated_by()
    {
        return $this->belongsTo(User::class, 'impersonated_by_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersLinkedProfiles()
    {
        return $this->hasMany(UserLinkedProfile::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersLinkedProfilesInverse()
    {
        return $this->hasMany(UserLinkedProfile::class, 'linked_user_id');
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
        return $this->hasMany(DashboardUserConfig::class);
    }

    public function dashboards () {
        return $this->BelongsToMany(Dashboard::class);
    }

    /**
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile-image');
    }

    /**
     * @return mixed
     */
    public function getRolesTrans()
    {
        return $this->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles_trans', function ($join) {
                $join->on(
                    'model_has_roles.role_id',
                    '=',
                    'roles_trans.role_id'
                )
                    ->where('roles_trans.locale', Auth::user()->locale);
            })
            ->select(['roles_trans.role_id as id', 'roles_trans.role_name'])
            ->where('users.id', $this->id)
            ->where('model_has_roles.model_type', '=', self::class)
            ->get();
    }

    /**
     * @return string
     */
    public function getActivityStatusAttribute(): string
    {
        if (is_null($this->last_activity) || !$this->logged) {
            return $this->attributes['activity_status'] = 'offline';
        }

        $lastActivity = Carbon::createFromFormat('Y-m-d H:i:s', $this->last_activity);
        $now = Carbon::now();
        $diff = $now->diffInMinutes($lastActivity);

        if ($diff > config('main.activity_status_time_intervals.offline')) {
            return $this->attributes['activity_status'] = 'offline';
        }
        if ($diff > config('main.activity_status_time_intervals.inactive')) {
            return $this->attributes['activity_status'] = 'inactive';
        }

        return $this->attributes['activity_status'] = 'active';
    }

    /**
     * @param string $needle
     * @return Collection
     */
    public static function search(string $needle): Collection
    {
        return self::where('name', 'LIKE', '%' . $needle . '%')
            ->orWhere('surname', 'LIKE', '%' . $needle . '%')
            ->get([
                DB::raw('CONCAT(users.name, " ", users.surname) as text'),
                DB::raw('users.id as id')
            ]);
    }

    /**
     * @return Collection
     */
    public function unreadMessages(): Collection
    {
        $unreadMessages = MessengerMessage::where(function ($query) {
            $query->where(function ($q) {
                $q->where('receiver_id', $this->id)
                    ->where('receiver_model', User::class)
                    ->whereNull('read_at');
            })->orWhere(function ($q) {
                $q->whereIn('role_id', $this->roles->pluck('id')->toArray())
                    ->where('receiver_model', Role::class)
                    ->whereNull('read_at');
            });
        })->get(['role_id', 'topic_id']);


        return $unreadMessages;
    }

    /**
     * @return Collection
     */
    public function unreadNotifications(): Collection
    {
        $now = Carbon::now()->toDateTimeString();
        $readNotificationsIds = $this->read_notifications ? array_pluck($this->read_notifications, 'id') : [];

        return Notification::where('start', '<=', $now)->where('end', '>', $now)
            ->whereHas('roles', function ($query) {
                $query->whereIn('roles.id', $this->roles->pluck('id')->toArray());
            })
            ->whereNotIn('notifications.id', $readNotificationsIds)
            ->groupBy('notifications.id')
            ->get();
    }

    /**
     * @return int
     */
    public function unreadEventsCount(): int
    {
        $roleIds = Auth::user()->roles->pluck('id')->toArray();
        $now = Carbon::now()->toDateTimeString();
        $readEventsIds = $this->read_events ? array_pluck($this->read_events, 'id') : [];

        $events = Event::where('end', '>=', $now)
            ->where(function ($query) use ($roleIds) {
                $query->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('roles.id', $roleIds);
                })->orWhereHas('users', function ($query) {
                    $query->where('users.id', $this->id);
                });
            })
            ->whereNotIn('events.id', $readEventsIds)
            ->groupBy('events.id')
            ->get(['id']);

        return $events->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestNotifications(): Collection
    {
        $now = Carbon::now()->toDateTimeString();
        $roleIds = $this->roles->pluck('id')->toArray();

        return Notification::where('start', '<=', $now)->where('end', '>', $now)
            ->whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('roles.id', $roleIds);
            })
            ->with('media')
            ->groupBy('notifications.id')
            ->get();
    }

    /**
     * Hash password
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * @return array|\Illuminate\Config\Repository|mixed
     */
    public function getWidgets()
    {
        $orderedWidgets = [];
        $widgets = config('main.dashboard.widgets');
        if (isset($this->settings['dashboard']['widgets'])) {
            foreach ($widgets as $widget) {
                $existOrder = false;
                foreach ($this->settings['dashboard']['widgets'] as $widgetOrder) {
                    if ($widget['id'] == $widgetOrder['id']) {
                        $orderedWidgets[] = $widgetOrder;
                        $existOrder = true;
                    }
                }
                if (!$existOrder) {
                    $orderedWidgets[] = $widget;
                }
            }
        } else {
            $orderedWidgets = $widgets;
        }

        return $orderedWidgets;
    }

    /**
     * @param $permission
     * @param null $guardName
     * @return bool
     */
    public function hasNotPermissionTo($permission, $guardName = null): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (!$permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }

        return !!$this->has_not_permissions->where('id', $permission->id)->count();
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|\Spatie\Permission\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $guardName);
        }

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (!$permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }

        if ($this->hasNotPermissionTo($permission)) {
            return false;
        }

        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    /**
     * @param string $permission
     * @return $this
     */
    public function removePermission($permission)
    {
        $permission = $this->getStoredPermission($permission);

        if ($this->hasDirectPermission($permission)) {
            $this->revokePermissionTo($permission);
        } else {
            $this->has_not_permissions()->attach($permission->id);
        }

        return $this;
    }

    /**
     * @param string $permission
     * @return $this
     */
    public function addPermission(string $permission)
    {
        $permission = $this->getStoredPermission($permission);

        if ($this->hasNotPermissionTo($permission)) {
            $this->has_not_permissions()->detach($permission->id);
        }
        $this->givePermissionTo($permission->id);

        return $this;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function hasPermissionToUser($userId)
    {
        if ($this->id == $userId) {
            return true;
        }

        return self::join('model_has_roles as mhr', 'mhr.model_id', 'users.id')
            ->join('role_sub_roles as rsr', 'rsr.role_id', 'mhr.role_id')
            ->where('users.id', $this->id)
            ->whereRaw('rsr.sub_role_id IN (
                SELECT mr.role_id FROM model_has_roles as mr WHERE mr.model_id = ' . $userId . '
            )')
            ->count();
    }

    /**
     * @return Role
     */
    public function getHighestLevelRole(): Role
    {
        return $this
            ->roles()
            ->orderBy('level', 'asc')
            ->first();
    }
}
