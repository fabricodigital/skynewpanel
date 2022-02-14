<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    Route::get('/homepage', 'HomeController@getHomeWdigetsJson')->name('homepagejson');
    Route::get('/homepage/widget-options', ['uses' => 'HomeController@getWidgetOptionsJson', 'as' => 'homepage.get-widget-options']);
    Route::put('/homepage/widget-reorder', ['uses' => 'HomeController@saveWidgetPositions', 'as' => 'homepage.widget-reorder']);

    Route::post('/dashboard/synctouser', ['uses' => 'DashboardUserController@syncDashboardToUser', 'as' => 'dashboard.synctouser']);
});
