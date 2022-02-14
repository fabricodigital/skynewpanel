<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Admin\Account' => 'App\Policies\Admin\AccountPolicy',
        'App\Models\Admin\User' => 'App\Policies\Admin\UserPolicy',
        'App\Models\Admin\Role' => 'App\Policies\Admin\RolePolicy',
        'App\Models\Admin\FaqCategory' => 'App\Policies\Admin\FaqCategoryPolicy',
        'App\Models\Admin\FaqQuestion' => 'App\Policies\Admin\FaqQuestionPolicy',
        'App\Models\Admin\MessengerTopic' => 'App\Policies\Admin\MessengerTopicPolicy',
        'App\Models\Admin\Notification' => 'App\Policies\Admin\NotificationPolicy',
        'App\Models\Admin\Event' => 'App\Policies\Admin\EventPolicy',
        'App\Models\Admin\MessengerMessage' => 'App\Policies\Admin\MessengerMessagePolicy',
        'App\Models\Admin\Revision' => 'App\Policies\Admin\RevisionPolicy',
        'App\Models\Admin\Export' => 'App\Policies\Admin\ExportPolicy',
        'App\Models\Admin\Permission' => 'App\Policies\Admin\PermissionPolicy',
        /* crud:create add policy */
        'App\Models\Admin\Note' => 'App\Policies\Admin\NotePolicy',
        'App\Models\Admin\Dashboard' => 'App\Policies\Admin\DashboardPolicy',
        'App\Models\Admin\Widget' => 'App\Policies\Admin\WidgetPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
