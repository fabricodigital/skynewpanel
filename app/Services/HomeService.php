<?php

namespace App\Services;

use App\Models\Admin\Account;
use App\Models\Admin\Dashboard;
use App\Models\Admin\DashboardUserConfig;
use App\Models\Admin\Note;
use App\Models\Admin\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HomeService
{
    protected $listChannels;
    protected $daterange;
    protected $daterangeCompare;
    protected $salesChannel;
    protected $salesChannelCompare;

    public function __construct($listChannels)
    {
        $this->listChannels = $listChannels;
    }

    public function setChannelDateRange($salesChannel = null, $salesChannelCompare = null, $daterange = null, $daterangeCompare = null)
    {
        $this->salesChannel = $salesChannel ?? '';
        $this->salesChannelCompare = $salesChannelCompare ?? '';

        if ($daterange) {
            $this->daterange = explode('-', $daterange);

            $this->startDate = trim($this->daterange[0]);
            $this->endDate   = trim($this->daterange[1]);
        } else {
            $this->startDate = today()->subMonth()->firstOfMonth()->format('d/m/Y');
            $this->endDate   = today()->subMonth()->lastOfMonth()->format('d/m/Y');
        }

        if ($daterangeCompare) {
            $this->daterangeCompare = explode('-', $daterangeCompare);

            $this->startDateCompare = trim($this->daterangeCompare[0]);
            $this->endDateCompare   = trim($this->daterangeCompare[1]);
        } else {
            $this->startDateCompare = Carbon::createFromFormat('d/m/Y', $this->startDate)->subMonth()->format('d/m/Y');
            $this->endDateCompare   = Carbon::createFromFormat('d/m/Y', $this->endDate)->subMonth()->format('d/m/Y');
        }
    }

    public function getHomeWdigets(User $user, $salesChannel = null, $salesChannelCompare = null, $daterange = null, $daterangeCompare = null, $dashboardId)
    {
        $this->setChannelDateRange($salesChannel, $salesChannelCompare, $daterange, $daterangeCompare);

        $dashboard = Dashboard::find($dashboardId);
        $widgets = $dashboard->widgets;

        # Change data format to be read correctly in the query
        $startDateSql = Carbon::createFromFormat('d/m/Y', $this->startDate)->format('Y-m-d');
        $endDateSql   = Carbon::createFromFormat('d/m/Y', $this->endDate)->format('Y-m-d');

        $startDateSqlCompare = Carbon::createFromFormat('d/m/Y', $this->startDateCompare)->format('Y-m-d');
        $endDateSqlCompare   = Carbon::createFromFormat('d/m/Y', $this->endDateCompare)->format('Y-m-d');

        foreach ($widgets as $widget) {
            $query_compare = $widget->query;

            if (str_contains($widget->query, 'startDate')) {
                $widget->query = str_replace('startDate', $startDateSql, $widget->query);
            }

            if (str_contains($widget->query, 'endDate')) {
                $widget->query = str_replace('endDate', $endDateSql, $widget->query);
            }

            if (str_contains($widget->query, 'salesChannel')) {
                $widget->query = str_replace('salesChannel', $this->salesChannel, $widget->query);
            }

            $widget->data = $this->prepareData(DB::select($widget->query), $widget->type);

            if (str_contains($query_compare, 'startDate')) {
                $query_compare = str_replace('startDate', $startDateSqlCompare, $query_compare);
            }

            if (str_contains($query_compare, 'endDate')) {
                $query_compare = str_replace('endDate', $endDateSqlCompare, $query_compare);
            }

            if (str_contains($query_compare, 'salesChannel')) {
                $query_compare = str_replace('salesChannel', $this->salesChannelCompare, $query_compare);
            }

            $widget->data_compare = $this->prepareData(DB::select($query_compare), $widget->type);

            $widgetOptions = DashboardUserConfig::where([
                'user_id' => $user->id,
                'dashboard_id' => $dashboard->id,
                'widget_id' => $widget->id,
            ])->first();

            $widget->options = optional($widgetOptions)->widget_settings;

            $notes = Note::where([
                ['user_id', '=', $user->id],
                ['widget_id', '=', $widget->id],
            ])->get()->toJson();

            $widget->notes = $notes ?? null;
        }

        return [
            'widgets' => $widgets,
            'user' => $user,
            'listChannels' => $this->listChannels,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'startDateCompare' => $this->startDateCompare,
            'endDateCompare' => $this->endDateCompare,
            'minDate' => $user->account->created_at->format('d/m/Y'),
            'maxDate' => today()->format('d/m/Y'),
            'salesChannel' => $this->salesChannel,
            'salesChannelCompare' => $this->salesChannelCompare,
        ];
    }

    public function getHomeStart(User $user, $salesChannel = null, $salesChannelCompare = null, $daterange = null, $daterangeCompare = null)
    {
        $this->setChannelDateRange($salesChannel, $salesChannelCompare, $daterange, $daterangeCompare);

        return [
            'user' => $user,
            'listChannels' => $this->listChannels,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'startDateCompare' => $this->startDateCompare,
            'endDateCompare' => $this->endDateCompare,
            'minDate' => $user->account->created_at->format('d/m/Y'),
            'maxDate' => today()->format('d/m/Y'),
            'salesChannel' => $this->salesChannel,
            'salesChannelCompare' => $this->salesChannelCompare,
        ];
    }

    private function prepareData($data, $type)
    {
        $labels = [];
        $values = [];
        $out = [];

        if ($type == 'datatable') {
            $keys = [];

            if (count($data)) {
                foreach ((array)$data[0] as $key => $riga) {
                    $keys[] = $key;
                }
                $out['columns'] = json_encode($keys);
                $out['rows'] = json_encode($data);
            }
        } else {
            if (isset($data) && count($data) > 0) {
                foreach ($data as $row) {
                    $labels[] = $row->label ?? 0;
                    $values[] = $row->value ?? 0;
                }
                $out = [
                    "labels" => $labels,
                    "values" => $values
                ];
            }
        }

        return $out;
    }
}
