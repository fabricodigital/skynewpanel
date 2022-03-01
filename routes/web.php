<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'AccountActivated', 'locale', 'UserLastActivityUpdater'], 'namespace' => 'Admin', 'as' => 'admin.',], function () {
    Route::get('/homepage/{salesChannel?}/{salesChannelCompare?}/{daterange?}/{daterangeCompare?}', ['uses' => 'HomeController@homepage', 'as' => 'homepage']);
    Route::put('/homepage/save-widget-options', ['uses' => 'HomeController@saveWidgetOptions', 'as' => 'homepage.save-widget-options']);
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
    Route::resource('accounts', 'AccountController');
    Route::post('/users/{user}/impersonate', ['uses' => 'UserController@postImpersonate', 'as' => 'users.impersonate']);
    Route::get('/users/impersonate-back', ['uses' => 'UserController@postImpersonateBack', 'as' => 'users.impersonate_back']);
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('faq-categories', 'FaqCategoryController');
    Route::resource('faq-questions', 'FaqQuestionController');
    Route::get('/faq', ['uses' => 'FaqShowController', 'as' => 'faq']);
    Route::resource('notifications', 'NotificationController');
    Route::post('/savenotes', ['uses' => 'NoteController@storeFromHomepage', 'as' => 'savenotes']);

    Route::get('order-promotion', ['uses' => 'PromotionController@orderPromotion', 'as' => 'orderpromotion']);

    /* Dashboard templates view, add and remove routes */
    Route::get('dashboard', ['uses' => 'DashboardUserController@index', 'as' => 'dashboard']);
    Route::get('dashboard/show/{dashboard}', ['uses' => 'DashboardUserController@show', 'as' => 'dashboard.show']);

    /* crud:create add resource route */
    Route::resource('promotions', 'PromotionController');
    Route::resource('notes', 'NoteController');
    Route::resource('dashboards', 'DashboardController');
    Route::resource('widgets', 'WidgetController');

    Route::get('/accounts/switch-account/{account}', ['uses' => 'AccountController@switchAccount', 'as' => 'accounts.switch-account']);

    Route::get('/search', ['uses' => 'UserVoucherController@search', 'as' => 'finclient']);
    Route::post('/searchclient', ['uses' => 'UserVoucherController@searchClient', 'as' => 'searchclient']);

    Route::group(['prefix' => 'messenger', 'as' => 'messenger.'], function () {
        Route::get('/', ['uses' => 'MessengerController@inbox', 'as' => 'index']);
        Route::post('/', ['uses' => 'MessengerController@store', 'as' => 'store']);
        Route::get('create', ['uses' => 'MessengerController@create', 'as' => 'create']);
        Route::get('inbox', ['uses' => 'MessengerController@inbox', 'as' => 'inbox']);
        Route::get('outbox', ['uses' => 'MessengerController@outbox', 'as' => 'outbox']);
        Route::post('{topic}/read', ['uses' => 'MessengerController@read', 'as' => 'read']);
        Route::get('{topic}', ['uses' => 'MessengerController@show', 'as' => 'show']);
        Route::put('{topic}', ['uses' => 'MessengerController@update', 'as' => 'update']);
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', ['uses' => 'ProfileController@edit', 'as' => 'edit']);
        Route::put('/', ['uses' => 'ProfileController@update', 'as' => 'update']);
        Route::get('locale', ['uses' => 'ProfileController@setLocale', 'as' => 'locale']);
        Route::post('avatar/{user?}', ['uses' => 'ProfileController@postAvatar', 'as' => 'avatar']);
        Route::post('link-profile', ['uses' => 'ProfileController@linkProfile', 'as' => 'link-profile']);
        Route::delete('destroy-linked-profile/{linked_user_id}', ['uses' => 'ProfileController@destroyLinkedProfile', 'as' => 'destroy-linked-profile']);
        Route::get('switch-linked-profile/{linked_user_id}', ['uses' => 'ProfileController@switchLinkedProfile', 'as' => 'switch-linked-profile']);
    });

    Route::get('calendar', ['uses' => 'CalendarController@index', 'as' => 'calendar']);
    Route::get('event', ['uses' => 'EventController@index', 'as' => 'event.index']);
    Route::post('event', ['uses' => 'EventController@store', 'as' => 'event.store']);
    Route::post('event/{event}', ['uses' => 'EventController@update', 'as' => 'event.update']);
    Route::delete('event/{event}', ['uses' => 'EventController@destroy', 'as' => 'event.destroy']);
    Route::post('event/{event}/read', ['uses' => 'EventController@read', 'as' => 'event.read']);

    Route::get('profile/locale', ['uses' => 'ProfileController@setLocale', 'as' => 'profile.locale']);
    Route::get('changelog', ['uses' => 'ChangelogController@index', 'as' => 'changelog.index']);
    Route::get('translations', ['uses' => 'TranslationController@index', 'as' => 'translations.index']);
    Route::post('translations/scan', ['uses' => 'TranslationController@postScanForStrings', 'as' => 'translations.scan']);
    Route::post('translations/export', ['uses' => 'TranslationController@postExportStrings', 'as' => 'translations.export']);
    Route::post('translations/import', ['uses' => 'TranslationController@postImportStrings', 'as' => 'translations.import']);

    Route::get('exports', ['uses' => 'ExportController@index', 'as' => 'exports.index']);
    Route::delete('exports/{export}', ['uses' => 'ExportController@destroy', 'as' => 'exports.destroy']);
    Route::post('exports/clear-old', ['uses' => 'ExportController@postClearOld', 'as' => 'exports.clear_old']);

    Route::get('revisions', ['uses' => 'RevisionController@index', 'as' => 'revisions.index']);

    Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
        Route::get('/', ['uses' => 'PermissionController@index', 'as' => 'index']);
    });

    Route::delete('ajax/media/{media}', ['uses' => 'MediaController@destroy', 'as' => 'ajax.media.destroy']);
    Route::post('ajax/media/{media}', ['uses' => 'MediaController@download', 'as' => 'ajax.media.download']);

    Route::group(['prefix' => 'ajax', 'middleware' => ['ajax'], 'as' => 'ajax.'], function () {
        Route::post('translations/update-strings', ['uses' => 'TranslationController@postUpdateStrings', 'as' => 'translations.update-string']);

        Route::get('users', ['uses' => 'UserController@ajaxSearch', 'as' => 'users']);
        Route::post('users/give-permission/{user}', ['uses' => 'UserController@ajaxGivePermission', 'as' => 'users.give-permission']);
        Route::post('users/revoke-permission/{user}', ['uses' => 'UserController@ajaxRevokePermission', 'as' => 'users.revoke-permission']);

        Route::post('profile/dashboard-order', ['uses' => 'ProfileController@postDashboardOrder', 'as' => 'profile.dashboard-order']);
        Route::post('profile/settings', ['uses' => 'ProfileController@postStoreSettings', 'as' => 'profile.store.settings']);
        Route::get('profile/settings', ['uses' => 'ProfileController@postGetSettings', 'as' => 'profile.show.settings']);

        Route::get('messenger/unread-topics', ['uses' => 'MessengerController@ajaxGetUnreadTopics', 'as' => 'messenger.unread-topics']);
        Route::get('notifications/unread-notifications', ['uses' => 'NotificationController@ajaxGetUnreadNotifications', 'as' => 'notifications.unread-notifications']);
        Route::get('notifications/latest-notification', ['uses' => 'NotificationController@ajaxGetLatestNotifications', 'as' => 'notifications.latest-notifications']);

        Route::post('notifications/{notification}/read', ['uses' => 'NotificationController@ajaxRead', 'as' => 'notifications.read']);


        Route::post('roles/give-permission/{role}', ['uses' => 'RoleController@ajaxGivePermission', 'as' => 'roles.give-permission']);
        Route::post('roles/revoke-permission/{role}', ['uses' => 'RoleController@ajaxRevokePermission', 'as' => 'roles.revoke-permission']);

        Route::get('permissions/table-view', ['uses' => 'PermissionController@ajaxGetTableView', 'as' => 'permissions.index']);

        Route::get('discussions/get-comments', ['uses' => 'DiscussionController@ajaxGetComments', 'as' => 'discussions.get-comments']);
        Route::post('discussions/save-comment', ['uses' => 'DiscussionController@ajaxSaveComment', 'as' => 'discussions.save-comment']);
    });

    Route::group(['prefix' => 'datatables', 'middleware' => ['ajax'], 'as' => 'datatables.'], function () {
        Route::post('accounts', ['uses' => 'AccountController@datatable', 'as' => 'accounts']);
        Route::post('users', ['uses' => 'UserController@datatable', 'as' => 'users']);
        Route::post('roles', ['uses' => 'RoleController@datatable', 'as' => 'roles']);
        Route::post('faq-categories', ['uses' => 'FaqCategoryController@datatable', 'as' => 'faq-categories']);
        Route::post('faq-questions', ['uses' => 'FaqQuestionController@datatable', 'as' => 'faq-questions']);
        Route::post('notifications', ['uses' => 'NotificationController@datatable', 'as' => 'notifications']);
        Route::post('revisions', ['uses' => 'RevisionController@datatable', 'as' => 'revisions']);
        Route::post('exports', ['uses' => 'ExportController@datatable', 'as' => 'exports']);

        /* crud:create add datatable route */
        Route::post('promotions', ['uses' => 'PromotionController@datatable', 'as' => 'promotions']);
        Route::post('notes', ['uses' => 'NoteController@datatable', 'as' => 'notes']);
        Route::post('dashboards', ['uses' => 'DashboardController@datatable', 'as' => 'dashboards']);
        Route::post('widgets', ['uses' => 'WidgetController@datatable', 'as' => 'widgets']);
    });

    Route::group([], function () {
        Route::post('faq-categories/restore/{id}', ['uses' => 'FaqCategoryController@restore', 'as' => 'faq-categories.restore']);
        Route::post('faq-questions/restore/{id}', ['uses' => 'FaqQuestionController@restore', 'as' => 'faq-questions.restore']);
        Route::post('exports/restore/{id}', ['uses' => 'ExportController@restore', 'as' => 'exports.restore']);
        Route::post('accounts/restore/{id}', ['uses' => 'AccountController@restore', 'as' => 'accounts.restore']);
        Route::post('users/restore/{id}', ['uses' => 'UserController@restore', 'as' => 'users.restore']);
        Route::post('roles/restore/{id}', ['uses' => 'RoleController@restore', 'as' => 'roles.restore']);
        Route::post('notifications/restore/{id}', ['uses' => 'NotificationController@restore', 'as' => 'notifications.restore']);

        /* crud:create add restore route */
        Route::post('promotions/restore/{id}', ['uses' => 'PromotionController@restore', 'as' => 'promotions.restore']);
        Route::post('notes/restore/{id}', ['uses' => 'NoteController@restore', 'as' => 'notes.restore']);
        Route::post('dashboards/restore/{id}', ['uses' => 'DashboardController@restore', 'as' => 'dashboards.restore']);
        Route::post('widgets/restore/{id}', ['uses' => 'WidgetController@restore', 'as' => 'widgets.restore']);
    });

    Route::post('datatables/save-user-view', ['uses' => 'DataTableController@postSaveUserView', 'as' => 'datatables.post.save_user_view']);
    Route::post('datatables/change-user-view', ['uses' => 'DataTableController@postChangeUserView', 'as' => 'datatables.post.change_user_view']);
    Route::delete('datatables/delete-user-view', ['uses' => 'DataTableController@postDeleteUserView', 'as' => 'datatables.post.delete_user_view']);
});

Route::group(['middleware' => ['GuestLocale']], function () {
    Auth::routes(['register' => false, 'verify' => false]);
    Route::get('guest-locale/{locale}', ['uses' => 'GuestController@getGuestLocale', 'as' => 'guest-locale']);
});
Route::get('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['guest', 'GuestLocale'], 'prefix' => 'account', 'namespace' => 'Auth', 'as' => 'account.'], function () {
    Route::get('request-activation-link', ['uses' => 'AccountActivationController@requestActivationLink', 'as' => 'request-activation-link']);
    Route::post('send-activation-link', ['uses' => 'AccountActivationController@sendActivationLink', 'as' => 'send-activation-link']);
    Route::get('activate', ['uses' => 'AccountActivationController@activationForm', 'as' => 'activate']);
    Route::post('activate', ['uses' => 'AccountActivationController@activate']);
});

Route::group(['middleware' => ['GuestLocale'], 'prefix' => 'account', 'namespace' => 'Auth', 'as' => 'account.'], function () {
    Route::get('activate-linked-profiles', ['uses' => 'AccountActivationController@activateLinkedProfiles', 'as' => 'activate-linked-profiles']);
});
