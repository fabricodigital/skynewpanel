<?php

namespace App\Http\Controllers\Admin;

use App\InternalEvent;
use App\ODL;
use App\Team;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use function __;
use function dd;
use function json_decode;
use function redirect;

class DataTableController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postChangeUserView()
    {
        $user = Auth::user();
        $targetTable = request('target_table');
        $viewName = request('view');

        $user->update([
            'settings->data_tables->' . $targetTable . '->last_used_view' => $viewName,
        ]);

        return redirect()->back();
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSaveUserView()
    {
        $user = Auth::user();
        $targetTable = request('target_table');
        $tableParams = request('table_params');
        $viewName = request('view_name') ?? 'Default';

        $user->update([
            'settings->data_tables->' . $targetTable . '->views->' . $viewName => json_decode($tableParams, true),
            'settings->data_tables->' . $targetTable . '->last_used_view' => $viewName,
        ]);

        return redirect()->back()
            ->with(['success' => __('Table view saved successfully!')]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteUserView()
    {
        $user = Auth::user();
        $targetTable = request('target_table');
        $viewName = request('view_name');

        if(isset($user->settings['data_tables'][$targetTable]['views'][$viewName])) {
            $tableViews = $user->settings['data_tables'][$targetTable]['views'];

            unset($tableViews[$viewName]);

            $user->update([
                'settings->data_tables->' . $targetTable . '->views' => $tableViews,
                'settings->data_tables->' . $targetTable . '->last_used_view' => $viewName,
            ]);
        }

        return redirect()->back()
            ->with(['success' => __('Table view deleted successfully!')]);
    }
}
