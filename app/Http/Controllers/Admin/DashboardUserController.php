<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Dashboard;
use App\Models\Admin\DashboardUserConfig;
use App\Models\Admin\Note;
use App\Models\Admin\User;
use App\Services\HomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardUserController extends Controller
{
    protected $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService(
            DB::table('amazon_report_products')->groupBy('sales_channel')->get()
        );
    }

    public function index()
    {
        $user = auth()->user();
        $account = $user->account;

        $dashboards = Dashboard::where(function ($query) use ($account) {
            $query->where('account_id', '=', $account->id)
                ->orWhereNull('account_id');
        })->whereIn('role_id', $user->roles->pluck('id')->toArray())->get();

        $logo = $account->getMedia('logo');
        if (count($logo) > 0) {
            $logo = $logo[0]->getFullUrl() ?? '';
        } else {
            $logo = '';
        }

        $data = [
            'user' => $user,
            'account' => $account,
            'dashboards' => $dashboards,
            'logo' => $logo,
        ];

        return view('admin.dashboard-template.index', $data);
    }

    public function show(Dashboard $dashboard)
    {
        $user = auth()->user();
        $account = $user->account;

        $logo = $user->account->getMedia('logo');
        if (count($logo) > 0) {
            $logo = $logo[0]->getFullUrl() ?? '';
        } else {
            $logo = '';
        }

        $data = $this->homeService->getHomeStart($user);

        $data['user'] = $user;
        $data['account'] = $account;
        $data['userDashboards'] = collect([$dashboard]);
        $data['logo'] = $logo;

        return view('admin.dashboard-template.show', $data);
    }

    public function syncDashboardToUser(Request $request)
    {

        $uid = explode(' ', chunk_split(trim($request->input('uid')), 60, " "));

        if ($uid[0] && $uid[1]) {
            $user = User::where([
                ['id', $uid[1]],
                ['password', $uid[0]],
            ])->first();
        }

        $dashboardId = (int)$request->input('dashboardId');
        $addDashboard = $request->input('addDashboard');
        $dashboard = Dashboard::find($dashboardId);

        if ($addDashboard == 'add') {
            $user->dashboards()->attach($dashboardId);
            foreach ($dashboard->widgets as $key => $widget) {
                DashboardUserConfig::updateOrCreate(
                    [
                        'widget_id' => $widget->id,
                        'dashboard_id' => $dashboardId,
                        'user_id' => $user->id,
                        'account_id' => $user->account->id,
                    ],
                    [
                        'widget_position' => $key,
                    ]
                );
            }
        } else if ($addDashboard == 'remove') {
            $user->dashboards()->detach($dashboardId);
            DashboardUserConfig::where([
                ['user_id', '=', $user->id],
                ['account_id', '=', $user->account->id],
                ['dashboard_id', '=', $dashboardId]
            ])->delete();

            # Delete associated notes
            if ($user->dashboards->isEmpty()) {
                Note::where('user_id', '=', $user->id)->forceDelete();
            }
        }
    }
}
