<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->globals();
        $this->messages();
        $this->notifications();
        $this->users();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function globals()
    {
        View::composer('*', function($view){
            $viewName = str_replace("::", ".", $view->getName());
            View::share('view_path_array',  explode(".", $viewName));
        });
    }

    private function messages()
    {
        $this->app->singleton('unreadMessages', function () {
            $unreadMessages = Auth::user()->unreadMessages();

            return $unreadMessages;
        });

        $this->app->singleton('unreadMessagesCount', function () {
            $unreadMessages = App::make('unreadMessages');

            $unreadMessagesCount = [
                'direct' => filter_direct_messages_count($unreadMessages),
                'help' => filter_help_messages_count($unreadMessages),
            ];

            return $unreadMessagesCount;
        });

        $this->app->singleton('unreadTopics', function() {
            $unreadMessages = App::make('unreadMessages');

            return \App\Models\Admin\MessengerTopic::whereIn('id', $unreadMessages->pluck('topic_id'))
                ->with('sender')
                ->with('userReceiver')
                ->with('roleReceiver')
                ->orderBy('sent_at', 'DESC')->get();
        });
    }

    private function notifications()
    {
        $this->app->singleton('unreadNotifications', function() {
            return Auth::user()->unreadNotifications();
        });

        $this->app->singleton('unreadNotificationsCount', function() {
            $unreadNotifications = App::make('unreadNotifications');

            return count($unreadNotifications);
        });
    }

    private function users()
    {
        $this->app->singleton('loggedUserRolesNames', function () {
            return Auth::user()->roles()
                ->pluck('role_name')
                ->toArray();
        });
    }
}
