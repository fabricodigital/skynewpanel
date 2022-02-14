<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DashboardUserConfig;
use App\Models\Admin\Note;
use App\Models\Admin\User;
use App\Models\Admin\Widget;
use App\Services\HomeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    private $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService(
            DB::table('amazon_report_products')->groupBy('sales_channel')->get()
        );
    }

    public function homepage($salesChannel = null, $salesChannelCompare = null, $daterange = null, $daterangeCompare = null)
    {
        $user = User::find(auth()->user()->id);
        $logo = $user->account->getMedia('logo');

        if (count($logo) > 0) {
            $logo = $logo[0]->getFullUrl() ?? '';
        } else {
            $logo = '';
        }

        $dashboards = [];

        foreach ($user->dashboards as $dashboard) {
            if (in_array($dashboard->role_id, $user->roles->pluck('id')->toArray())) {
                if ($dashboard->account_id == $user->account_id || $dashboard->account_id == null) {
                    $getWidgets = DB::select(
                        'SELECT w.*
                        FROM dashboards AS d
                        INNER JOIN dashboard_widget AS dw ON dw.dashboard_id = d.id
                        INNER JOIN widgets AS w ON dw.widget_id = w.id
                        INNER JOIN dashboard_user_configs AS duc ON duc.widget_id = w.id AND duc.dashboard_id = d.id
                        WHERE d.id = ?
                        ORDER BY duc.widget_position ASC',
                        [$dashboard->id]
                    );

                    if (count($getWidgets) > 0) {
                        $widgets = [];

                        foreach ($getWidgets as $widget) {
                            $widgets[] = Widget::find($widget->id);
                        };

                        $dashboard->widgets = collect($widgets);
                        $dashboards[] = $dashboard;
                    } else {
                        $dashboards[] = $dashboard;
                    }
                }
            }
        }

        $data = $this->homeService->getHomeStart(
            $user,
            str_replace('-', '', $salesChannel),
            str_replace('-', '', $salesChannelCompare),
            $daterange ? str_replace('@', '/', $daterange) : null,
            $daterangeCompare ? str_replace('@', '/', $daterangeCompare) : null,
        );

        $data['userDashboards'] = collect($dashboards);
        $data['logo'] = $logo;

        return view('admin.homepage', $data);
    }

    public function index()
    {
        $user = User::find(auth()->user()->id);
        $logo = $user->account->getMedia('logo');

        if (count($logo) > 0) {
            $logo = $logo[0]->getFullUrl() ?? '';
        } else {
            $logo = '';
        }

        $dashboards = [];

        foreach ($user->dashboards as $dashboard) {
            if (in_array($dashboard->role_id, $user->roles->pluck('id')->toArray())) {
                if ($dashboard->account_id == $user->account_id || $dashboard->account_id == null) {
                    $getWidgets = DB::select(
                        'SELECT w.*
                        FROM dashboards AS d
                        INNER JOIN dashboard_widget AS dw ON dw.dashboard_id = d.id
                        INNER JOIN widgets AS w ON dw.widget_id = w.id
                        INNER JOIN dashboard_user_configs AS duc ON duc.widget_id = w.id AND duc.dashboard_id = d.id
                        WHERE d.id = ?
                        ORDER BY duc.widget_position ASC',
                        [$dashboard->id]
                    );

                    if (count($getWidgets) > 0) {
                        $widgets = [];

                        foreach ($getWidgets as $widget) {
                            $widgets[] = Widget::find($widget->id);
                        };

                        $dashboard->widgets = collect($widgets);
                        $dashboards[] = $dashboard;
                    } else {
                        $dashboards[] = $dashboard;
                    }
                }
            }
        }

        $data = $this->homeService->getHomeStart($user);
        $data['userDashboards'] = collect($dashboards);
        $data['logo'] = $logo;
        return view('admin.home', $data);
    }

    public function getHomeWdigetsJson(Request $request)
    {
        $uid = explode(' ', chunk_split(trim($request->input('uid')), 60, " "));

        if ($uid[0] && $uid[1]) {
            $user = User::where([
                ['id', $uid[1]],
                ['password', $uid[0]],
            ])->first();

            $data = $this->homeService->getHomeWdigets(
                $user,
                $request->input('salesChannel') ?? null,
                $request->input('salesChannelCompare') ?? null,
                $request->input('daterange') ?? null,
                $request->input('daterangeCompare') ?? null,
                $request->input('dashboardId')
            );

            return response()->json($data);
        }
    }

    public function saveWidgetOptions(Request $request)
    {
        $data = [
            'title' => $request->input('title'),
            'ionIcon' => $request->input('ion-icon'),
            'widgetOptions' => $request->input('widget-options'),
            'backgroundColorIcon' => $request->input('background-color-icon') != '0' ? $request->input('background-color-icon') : null,
            'prefixDivider' => $request->input('prefix-divider'),
            'decimals' => $request->input('decimals'),
            'freeFieldPrefix' => $request->input('free-field-prefix'),
            'freeFieldSuffix' => $request->input('free-field-suffix'),
        ];

        $user = auth()->user();

        DashboardUserConfig::updateOrCreate(
            [
                'user_id' => $user->id,
                'dashboard_id' => $request->input('dashboard_id'),
                'widget_id' => $request->input('widget_id'),
            ],
            [
                'account_id' => $user->account->id,
                'widget_settings' => json_encode($data),
            ]
        );

        return redirect()->route(
            'admin.homepage',
            [
                'salesChannel' => $request->input('salesChannel') ? $request->input('salesChannel') : '-',
                'salesChannelCompare' => $request->input('salesChannelCompare') ? $request->input('salesChannelCompare') : '-',
                'daterange' => $request->input('daterange') ? str_replace('/', '@', $request->input('daterange')) : null,
                'daterangeCompare' => $request->input('daterangeCompare') ? str_replace('/', '@', $request->input('daterangeCompare')) : null,
            ]
        );
    }

    public function saveWidgetPositions(Request $request)
    {
        $uid = explode(' ', chunk_split(trim($request->input('uid')), 60, " "));

        if ($uid[0] && $uid[1]) {
            $user = User::where([
                ['id', $uid[1]],
                ['password', $uid[0]],
            ])->first();

            $dashboardId = $request->input('dashboardId');

            if (optional($user)) {
                if ($user->dashboards->isNotEmpty()) {
                    foreach ($request->input('itemWidgets') as $key => $widget_id) {
                        DashboardUserConfig::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'account_id' => $user->account->id,
                                'dashboard_id' => $dashboardId,
                                'widget_id' => $widget_id,
                            ],
                            [
                                'widget_position' => $key + 1,
                            ]
                        );
                    }
                }
            }
        }
    }

    public function getWidgetOptionsJson(Request $request)
    {
        $uid = explode(' ', chunk_split(trim($request->input('uid')), 60, " "));

        if ($uid[0] && $uid[1]) {
            $user = User::where([
                ['id', $uid[1]],
                ['password', $uid[0]],
            ])->first();

            $widgetId = $request->input('widget_id');
            $dashboard_id = $request->input('dashboard_id');

            if (optional($user)) {
                $widgetOptions = DashboardUserConfig::where([
                    'user_id' => $user->id,
                    'dashboard_id' => $dashboard_id,
                    'widget_id' => $widgetId,
                ])->first();

                $widgetName = Widget::find($widgetId)->name;

                $notes = Note::where([
                    ['user_id', '=', $user->id],
                    ['widget_id', '=', $widgetId],
                ])->get()->toJson();

                return response()->json([
                    'name' => $widgetName,
                    'options' => optional($widgetOptions)->widget_settings,
                    'notes' => $notes,
                ]);
            }
        }
    }
}
