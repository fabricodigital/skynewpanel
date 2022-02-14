@extends('layouts.admin')

@section('title', __('Dashboard'))

<link rel="stylesheet" href="{{ asset('homepagecss/css/style.css') }}" />

@section('content')
@include('partials._content-heading', ['title' => __('Dashboard')])

<div class="row">
    <section class="col-lg-12">
        @include('widgets.notifications')
    </section>
</div>

<div class="row">
    <section class="col-lg-12 dashboard-widgets">
        @if($userDashboards->isNotEmpty())
        <div class="grid-stack row drag-container widget-container">
            <div class="form-row export-wrapper">
                <form method="post" action="{{ route('admin.home') }}">
                    @csrf
                    <div class="form-inline-home">
                        <div class="col-auto spacing-padding" style="margin-bottom: 2%">
                            <select class="form-control" id="sales_channel" name="sales_channel">
                                <option value="">All channels</option>
                                @foreach ($listChannels as $channel)
                                <option value="{{ $channel->sales_channel }}" {{ $salesChannel==$channel->sales_channel
                                    ?
                                    'selected' : '' }}>
                                    {{ $channel->sales_channel }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto spacing-padding" style="margin-bottom: 2%">
                            <input type="text" id="date_subscribers" class="form-control" name="daterange"
                                placeholder="Seleziona range data" value="{{ $startDate . ' - ' . $endDate }}" />
                        </div>
                        <div class="col-auto spacing-padding" style="margin-bottom: 2%">
                            <a href="#" id="form-submit-select" class="btn btn-primary link-light"
                                style="border-radius: 4px !important;">{{ __('SELECT') }}</a>
                        </div>
                    </div>

                    <div class="form-inline-home">
                        <div class="col-auto spacing-padding" style="margin-bottom: 2%">
                            <select class="form-control" name="sales_channel_compare">
                                <option value="">All channels</option>
                                @foreach ($listChannels as $channel)
                                <option value="{{ $channel->sales_channel }}" {{ $salesChannelCompare==$channel->
                                    sales_channel ? 'selected' : '' }}>
                                    {{ $channel->sales_channel }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto spacing-padding" style="margin-bottom: 2%">
                            <input type="text" id="date_subscribers" class="form-control" name="daterange_compare"
                                placeholder="Seleziona range data"
                                value="{{ $startDateCompare . ' - ' . $endDateCompare }}" />
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="custom-container in-dash">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @foreach ($userDashboards as $dashboard)
                <li class="nav-item {{ $loop->first ? 'active' : '' }}">
                    <a class="nav-link custom-nav {{ $loop->first ? 'active' : '' }}"
                        id="{{  $dashboard->name_slugged }}-tab" data-toggle="tab"
                        href="#{{  $dashboard->name_slugged }}" role="tab"
                        aria-controls="{{  $dashboard->name_slugged }}" aria-selected="true">{{ $dashboard->name
                        }}</a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="myTabContent">
                @foreach ($userDashboards as $dashboard)
                <div class="tab-pane fade {{ $loop->first ? " active show in" : "" }} custom-tab"
                    id="{{  $dashboard->name_slugged }}" role="tabpanel"
                    aria-labelledby="{{  $dashboard->name_slugged }}-tab">
                    <div class="grid-stack drag-container widget-container">
                        <div class="row area-widgets-{{ $dashboard->id }}" data-dashboard-id="{{ $dashboard->id }}">
                            @if ($dashboard->widgets->isNotEmpty())
                            @foreach ($dashboard->widgets as $widget)
                            @switch($widget->type)
                            @case('kpi')
                            <div class="col-lg-{{ $widget->width }} col-sm-{{ $widget->width }} drag-item parent-dashboard-{{ $dashboard->id }}"
                                draggable="true" data-id="{{ $widget->id }}">
                                <div class="widget-box widget-wrap">
                                    @include('partials.widgets.kpi')
                                </div>
                            </div>
                            @break
                            @endswitch
                            @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="grid-stack drag-container widget-container">
                        <div class="row area-widgets-{{ $dashboard->id }}" data-dashboard-id="{{ $dashboard->id }}">
                            @if ($dashboard->widgets->isNotEmpty())
                            @foreach ($dashboard->widgets as $widget)
                            @if ($widget->type != 'kpi' && $widget->type != 'datatable')
                            <div class="col-lg-{{ $widget->width }} col-sm-{{ $widget->width }} drag-item parent-dashboard-{{ $dashboard->id }}"
                                draggable="true" data-id="{{ $widget->id }}">
                                <div class="widget-box widget-wrap">
                                    <div class="widget-box__title">
                                        <div class="widget-icons">
                                            @if ($widget->type == 'bar')
                                            <div class="icon icon-bar" @if(optional($widget->
                                                dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first())->widget_settings)
                                                style="background-color:{{
                                                json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first()->widget_settings, true)['backgroundColorIcon'] }}"
                                                @endif
                                                >
                                                <ion-icon @if(optional($widget->
                                                    dashboardUserConfigs->where('dashboard_id', '=',
                                                    $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first())->widget_settings)
                                                    name="{{
                                                    json_decode($widget->dashboardUserConfigs->where('dashboard_id',
                                                    '=', $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first()->widget_settings, true)['ionIcon'] }}"
                                                    @else
                                                    name="cellular-outline"
                                                    @endif
                                                    >
                                                </ion-icon>
                                            </div>
                                            @elseif ($widget->type == 'line')
                                            <div class="icon icon-line" @if(optional($widget->
                                                dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first())->widget_settings)
                                                style="background-color:{{
                                                json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first()->widget_settings, true)['backgroundColorIcon'] }}"
                                                @endif
                                                >
                                                <ion-icon @if(optional($widget->
                                                    dashboardUserConfigs->where('dashboard_id', '=',
                                                    $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first())->widget_settings)
                                                    name="{{
                                                    json_decode($widget->dashboardUserConfigs->where('dashboard_id',
                                                    '=', $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first()->widget_settings, true)['ionIcon'] }}"
                                                    @else
                                                    name="analytics-outline"
                                                    @endif
                                                    >
                                                </ion-icon>
                                            </div>
                                            @else
                                            <div class="icon icon-pie" @if(optional($widget->
                                                dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first())->widget_settings)
                                                style="background-color:{{
                                                json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first()->widget_settings, true)['backgroundColorIcon'] }}"
                                                @endif
                                                >
                                                <ion-icon @if(optional($widget->
                                                    dashboardUserConfigs->where('dashboard_id', '=',
                                                    $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first())->widget_settings)
                                                    name="{{
                                                    json_decode($widget->dashboardUserConfigs->where('dashboard_id',
                                                    '=', $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first()->widget_settings, true)['ionIcon'] }}"
                                                    @else
                                                    name="pie-chart-outline"
                                                    @endif
                                                    >
                                                </ion-icon>
                                            </div>
                                            @endif
                                            <strong>
                                                @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                                                    {{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['title']?? $widget->name }}
                                                @else
                                                    {{ $widget->name }}
                                                @endif
                                            </strong>
                                        </div>
                                        <a href="#" data-toggle="modal" data-target="#myModal"
                                            onClick="getWidgetOptions('{{ $widget->id }}', '{{ $widget->type }}', '{{ $dashboard->id }}')"
                                            style="border: 0px solid transparent;background-color: transparent;color: black">
                                            <ion-icon name="construct-outline"></ion-icon>
                                        </a>
                                    </div>
                                    <div class="widget-box__body">
                                        <canvas id="{{ $dashboard->id }}-chart-{{ $widget->id }}"></canvas>
                                    </div>
                                </div>
                            </div>
                            @elseif($widget->type == 'datatable')
                            <div class="col-lg-{{ $widget->width }} col-sm-{{ $widget->width }} drag-item parent-dashboard-{{ $dashboard->id }}"
                                draggable="true" data-id="{{ $widget->id }}">
                                <div class="widget-box widget-wrap">
                                    <div class="widget-box__title">
                                        <div class="widget-icons">
                                            <div class="icon icon-bar" @if(optional($widget->
                                                dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first())->widget_settings)
                                                style="background-color:{{
                                                json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=',
                                                $dashboard->id)->where('user_id', '=',
                                                $user->id)->first()->widget_settings, true)['backgroundColorIcon'] }}"
                                                @endif
                                                >
                                                <ion-icon @if(optional($widget->
                                                    dashboardUserConfigs->where('dashboard_id', '=',
                                                    $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first())->widget_settings)
                                                    name="{{
                                                    json_decode($widget->dashboardUserConfigs->where('dashboard_id',
                                                    '=', $dashboard->id)->where('user_id', '=',
                                                    $user->id)->first()->widget_settings, true)['ionIcon']??
                                                    'filter-outline' }}"
                                                    @else
                                                    name="filter-outline"
                                                    @endif
                                                    >
                                                </ion-icon>
                                            </div>
                                            <strong>
                                                @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                                                    {{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['title']?? $widget->name }}
                                                @else
                                                    {{ $widget->name }}
                                                @endif
                                            </strong>
                                        </div>
                                        <a href="#" data-toggle="modal" data-target="#myModal"
                                            onClick="getWidgetOptions('{{ $widget->id }}', '{{ $widget->type }}', '{{ $dashboard->id }}')"
                                            style="border: 0px solid transparent;background-color: transparent;color: black">
                                            <ion-icon name="construct-outline"></ion-icon>
                                        </a>
                                    </div>
                                    <div class="widget-box__body datatable-js">
                                        <table id="{{ $dashboard->id }}-datatable-js-{{ $widget->id }}" class="display"
                                            style="width:100%">
                                            <thead>
                                                <tr id="{{ $dashboard->id }}-datatable-js-tr-head-{{ $widget->id }}">

                                                </tr>
                                            </thead>
                                            <tbody id="{{ $dashboard->id }}-datatable-js-body-{{ $widget->id }}">

                                            </tbody>
                                            <tfoot>
                                                <tr id="{{ $dashboard->id }}-datatable-js-tr-foot-{{ $widget->id }}">

                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>
</div>
@stop

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('homepagecss/plugins/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('homepagecss/plugins/bootstrap/bootstrap.min.js') }}"></script>

@section('javascript')
@parent


<script>
    $.noConflict();

        var dashboardId = [];

        @if ($userDashboards->isNotEmpty())
            @foreach ($userDashboards as $key => $dashboard)
                dashboardId[{{ $key }}] = '{{ $dashboard->id }}';
            @endforeach
        @endif

        jQuery(document).ready(function($) {

            var daterange = $("input[type=text][name=daterange]").val();
            var daterangeCompare = $("input[type=text][name=daterange_compare]").val();
            var salesChannel = $("#sales_channel").val();
            var salesChannelCompare = $("#sales_channel_compare").val();

            jQuery("#body").loading();

            if(dashboardId.length > 0){
                dashboardId.forEach(function(id){
                    renderCharts(daterange, daterangeCompare, salesChannel, salesChannelCompare, id);
                });
            } else {
                jQuery("#body").loading('stop');
            }

            function submitSelect() {
                var daterange = $("input[type=text][name=daterange]").val();
                var daterangeCompare = $("input[type=text][name=daterange_compare]").val();
                var salesChannel = $("#sales_channel").val();
                var salesChannelCompare = $("#sales_channel_compare").val();

                jQuery("#body").loading();

                if(dashboardId.length > 0){
                    dashboardId.forEach(function(id){
                        updateCharts(daterange, daterangeCompare, salesChannel, salesChannelCompare, id);
                    });
                } else {
                    jQuery("#body").loading('stop');
                }
            }

            $('#form-submit-select').click(function(){
                submitSelect();
            });

            $(function() {
                var settings = {
                    opens: 'left',
                    showCustomRangeLabel: true,
                    alwaysShowCalendars: true,
                    locale: {
                        format: 'DD/MM/YYYY',
                        applyLabel: "Applica",
                        cancelLabel: "Cancella",
                        fromLabel: "Da",
                        toLabel: "a",
                        customRangeLabel: "Personalizza",
                        daysOfWeek: [
                            "Dom",
                            "Lun",
                            "Mar",
                            "Mer",
                            "Gio",
                            "Ven",
                            "Sab",
                        ],
                        monthNames: [
                            "Gennaio",
                            "Febbraio",
                            "Marzo",
                            "Aprile",
                            "Maggio",
                            "Giugno",
                            "Luglio",
                            "Agosto",
                            "Settembre",
                            "Ottobre",
                            "Novembre",
                            "Dicembre"
                        ],
                        "firstDay": 1
                    },
                    ranges: {
                        'Oggi': [moment(), moment()],
                        'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
                        'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
                        'Questo mese': [moment().startOf('month'), moment().endOf('month')],
                        'Ultimo mese': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: "{{ $startDate }}",
                    endDate: "{{ $endDate }}",
                    minDate: "{{ $minDate }}",
                    maxDate: "{{ $maxDate }}",
                }

                var settings_compare = settings;

                $('input[name="daterange"]').daterangepicker(settings, function(start, end, label) {

                });

                settings_compare.startDate = "{{ $startDateCompare }}";
                settings_compare.endDate = "{{ $endDateCompare }}";

                $('input[name="daterange_compare"]').daterangepicker(settings_compare, function(start, end, label) {

                });
            });

            var multipleCharts = [];

            function renderCharts(daterange, daterangeCompare, salesChannel, salesChannelCompare, dashboardId){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ route('admin.homepagejson') }}",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        _token: CSRF_TOKEN,
                        uid : "{{ auth()->user()->password .''. auth()->user()->id }}",
                        daterange : daterange,
                        daterangeCompare : daterangeCompare,
                        salesChannel : salesChannel,
                        salesChannelCompare : salesChannelCompare,
                        dashboardId : dashboardId,
                    },
                    success: function (response) {
                        jQuery("#body").loading('stop');

                        var widgets = response.widgets;

                        widgets.forEach(function (element) {
                            if(element.type == 'kpi'){
                                var data_values = element.data.values[0] ? element.data.values[0] : 0;
                                var data_compare_values = element.data_compare.values[0] ? element.data_compare.values[0] : 0;

                                var options = element.options? JSON.parse(element.options) : null;
                                var prefix = '';
                                var suffix = '';

                                if(options){
                                    // divider per kilo or mega
                                    if(options.prefixDivider !== "0"){
                                        data_values = data_values ? (data_values / options.prefixDivider) : 0;
                                        data_compare_values = data_compare_values ? (data_compare_values / options.prefixDivider) : 0;
                                    }

                                    var decimals = 0;

                                    // add or remove decimals
                                    if(options.decimals !== "0"){
                                        data_values = parseFloat(data_values).toFixed(options.decimals);
                                        data_compare_values = parseFloat(data_compare_values).toFixed(options.decimals);
                                        decimals = options.decimals;
                                    } else {
                                        data_values = Math.round(data_values);
                                        data_compare_values = Math.round(data_compare_values);
                                    }

                                    // add prefix and suffix
                                    prefix = options.freeFieldPrefix ?? '';
                                    suffix = options.freeFieldSuffix ?? '';
                                } else {
                                    var decimals = 2;
                                    data_values = parseFloat(data_values).toFixed(decimals);
                                    data_compare_values = parseFloat(data_compare_values).toFixed(decimals);
                                }

                                var feedback = document.getElementById('feedback-valore-'+dashboardId+'-'+element.id);

                                if(feedback){
                                    if(data_values > data_compare_values) {
                                       feedback.innerHTML = `<div class="up"><span>${ (data_values - data_compare_values).toFixed(decimals) }</span><ion-icon name="arrow-up-outline"></ion-icon></div>`;
                                    } else if (data_values < data_compare_values) {
                                        feedback.innerHTML = `<div class="down"><span>${ (data_values - data_compare_values).toFixed(decimals) }</span><ion-icon name="arrow-down-outline"></ion-icon></div>`;
                                    } else {
                                        feedback.innerHTML = `<div class="neutral"><ion-icon name="remove-outline"></ion-icon></div>`;
                                    }

                                    document.getElementById('kpi-value-'+dashboardId+'-'+element.id).innerHTML = prefix + ' ' + data_values + ' ' + suffix;
                                    document.getElementById('old-value-'+dashboardId+'-'+element.id).innerHTML = prefix + ' ' + data_compare_values + ' ' + suffix;
                                }

                            } else if(element.type == 'datatable') {

                                var columns = element.data.columns? JSON.parse(element.data.columns) : null;
                                var rows = element.data.rows? JSON.parse(element.data.rows) : null;

                                if(columns){
                                    var table_th = '';
                                    var table_js = '';
                                    columns.forEach(function(column){
                                        table_th = table_th + `<th>${column}</th>`;
                                    });

                                    document.getElementById(dashboardId+'-datatable-js-tr-head-'+element.id).innerHTML = table_th;
                                    document.getElementById(dashboardId+'-datatable-js-tr-foot-'+element.id).innerHTML = table_th;

                                    if(rows){
                                        var table_tr = '';
                                        rows.forEach(function(row){
                                                var table_td = '';
                                                columns.forEach(function(column){
                                                    table_td = table_td + `<td>${row[column]}</td>`;
                                                });

                                                table_tr = table_tr + `<tr>${table_td}</tr>`;
                                        });

                                        document.getElementById(dashboardId+'-datatable-js-body-'+element.id).innerHTML = table_tr;

                                        $('#'+dashboardId+'-datatable-js-'+element.id).DataTable();
                                    }

                                }
                            } else {
                                var data_labels = element.data.labels ? element.data.labels : 0;
                                var data_values = element.data.values ? element.data.values : 0;
                                var data_compare_labels = element.data_compare.labels ? element.data_compare.labels : 0;
                                var data_compare_values = element.data_compare.values ? element.data_compare.values : 0;

                                var borderColor = '#009EF7';
                                var backgroundColor = '#A9DEFD';
                                var borderColorCompare = '#F7DC6F';
                                var backgroundColorCompare = '#FBE7C6';

                                if(element.type == 'line'){
                                    backgroundColor = 'rgb(232, 255, 243, 0.6)';
                                    borderColor = '#50CD89';
                                    var borderColorCompare = '#F7DC6F';
                                    var backgroundColorCompare = 'rgb(251, 231, 198, 0.6)';
                                }

                                var data = {
                                        labels: data_labels,
                                            datasets: [
                                                {
                                                    label: element.name,
                                                    backgroundColor: backgroundColor,
                                                    borderColor: borderColor,
                                                    borderWidth: 3,
                                                    pointRadius: 4,
                                                    pointHoverRadius: 4,
                                                    lineTension: 0.4,
                                                    radius: 2,
                                                    fill: true,
                                                    data: data_values,
                                                },
                                                {
                                                    label: element.name,
                                                    backgroundColor: backgroundColorCompare,
                                                    borderColor: borderColorCompare,
                                                    borderWidth: 3,
                                                    pointRadius: 4,
                                                    pointHoverRadius: 4,
                                                    lineTension: 0.4,
                                                    radius: 2,
                                                    fill: true,
                                                    data: data_compare_values,
                                                }
                                            ]
                                        };

                                var options = {
                                        scales: {
                                            x: {
                                                grid: {
                                                    borderDash: [5],
                                                    color: 'rgba(0, 0, 0, 0.05)',
                                                },
                                            },
                                            y: {
                                                grid: {
                                                    borderDash: [5],
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                },
                                            },
                                        },
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: false,
                                                text: element.name,
                                            },
                                            tooltip: {
                                                boxPadding: 5,
                                                padding: 10,
                                                backgroundColor: '#333',
                                                titleFont: {
                                                    size: 12,
                                                    weight: 'normal'
                                                },
                                                bodyFont: {
                                                    size: 15,
                                                    weight: 'bold'
                                                },
                                                callbacks: {
                                                    title: function(tooltipItem){
                                                        if(tooltipItem[0].datasetIndex === 1 && ['line', 'bar'].includes(element.type)){
                                                            return data_compare_labels[tooltipItem[0].dataIndex];
                                                        } else {
                                                            return tooltipItem[0].label;
                                                        }
                                                    },
                                                }
                                            }
                                        },
                                }

                                var ctx = document.getElementById(dashboardId+'-chart-'+element.id);

                                var index = dashboardId+element.id;

                                if(ctx){
                                        multipleCharts[index] = new Chart(ctx, {
                                            type: element.type,
                                            data: data,
                                            options: options,
                                        });
                                }
                            }
                        });
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }

            function updateCharts(daterange, daterangeCompare, salesChannel, salesChannelCompare, dashboardId){
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('admin.homepagejson') }}",
                    type: 'get',
                    dataType: 'json',
                    data: {
                        _token: CSRF_TOKEN,
                        uid : "{{ auth()->user()->password .''. auth()->user()->id }}",
                        daterange : daterange,
                        daterangeCompare : daterangeCompare,
                        salesChannel : salesChannel,
                        salesChannelCompare : salesChannelCompare,
                        dashboardId : dashboardId,
                    },
                    success: function (response) {
                        jQuery("#body").loading('stop');

                        var widgets = response.widgets;

                        widgets.forEach(function (element) {
                            if(element.type == 'kpi'){
                                var data_values = element.data.values[0] ? element.data.values[0] : 0;
                                var data_compare_values = element.data_compare.values[0] ? element.data_compare.values[0] : 0;

                                var options = element.options ? JSON.parse(element.options) : null;
                                var prefix = '';
                                var suffix = '';

                                if(options){

                                    // divider per kilo or mega
                                    if(options.prefixDivider !== "0"){
                                        data_values = data_values ? (data_values / options.prefixDivider) : 0;
                                        data_compare_values = data_compare_values ? (data_compare_values / options.prefixDivider) : 0;
                                    }

                                    var decimals = 0;

                                    // add or remove decimals
                                    if(options.decimals !== "0"){
                                        data_values = parseFloat(data_values).toFixed(options.decimals);
                                        data_compare_values = parseFloat(data_compare_values).toFixed(options.decimals);
                                        decimals = options.decimals;
                                    } else {
                                        data_values = Math.round(data_values);
                                        data_compare_values = Math.round(data_compare_values);
                                    }

                                    // add prefix and suffix
                                    prefix = options.freeFieldPrefix ?? '';
                                    suffix = options.freeFieldSuffix ?? '';
                                } else {
                                    var decimals = 2;
                                    data_values = parseFloat(data_values).toFixed(decimals);
                                    data_compare_values = parseFloat(data_compare_values).toFixed(decimals);
                                }

                                var feedback = document.getElementById('feedback-valore-'+dashboardId+'-'+element.id);

                                if(feedback){
                                    if(data_values > data_compare_values) {
                                       feedback.innerHTML = `<div class="up"><span>${ (data_values - data_compare_values).toFixed(decimals) }</span><ion-icon name="arrow-up-outline"></ion-icon></div>`;
                                    } else if (data_values < data_compare_values) {
                                        feedback.innerHTML = `<div class="down"><span>${ (data_values - data_compare_values).toFixed(decimals) }</span><ion-icon name="arrow-down-outline"></ion-icon></div>`;
                                    } else {
                                        feedback.innerHTML = `<div class="neutral"><ion-icon name="remove-outline"></ion-icon></div>`;
                                    }

                                    document.getElementById('kpi-value-'+dashboardId+'-'+element.id).innerHTML = prefix + ' ' + data_values + ' ' + suffix;
                                    document.getElementById('old-value-'+dashboardId+'-'+element.id).innerHTML = prefix + ' ' + data_compare_values + ' ' + suffix;
                                }

                            } else if(element.type == 'datatable') {

                                var columns = element.data.columns? JSON.parse(element.data.columns) : null;
                                var rows = element.data.rows? JSON.parse(element.data.rows) : null;

                                if(columns){
                                    var table_th = '';
                                    var table_js = '';
                                    columns.forEach(function(column){
                                        table_th = table_th + `<th>${column}</th>`;
                                    });

                                    document.getElementById(dashboardId+'-datatable-js-tr-head-'+element.id).innerHTML = table_th;
                                    document.getElementById(dashboardId+'-datatable-js-tr-foot-'+element.id).innerHTML = table_th;

                                    if(rows){
                                        var table_tr = '';
                                        rows.forEach(function(row){
                                                var table_td = '';
                                                columns.forEach(function(column){
                                                    table_td = table_td + `<td>${row[column]}</td>`;
                                                });

                                                table_tr = table_tr + `<tr>${table_td}</tr>`;
                                        });

                                        document.getElementById(dashboardId+'-datatable-js-body-'+element.id).innerHTML = table_tr;

                                        $('#'+dashboardId+'-datatable-js-'+element.id).DataTable();
                                    }

                                }
                            } else {

                                var data_labels = element.data.labels ? element.data.labels : 0;
                                var data_values = element.data.values ? element.data.values : 0;
                                var data_compare_labels = element.data_compare.labels ? element.data_compare.labels : 0;
                                var data_compare_values = element.data_compare.values ? element.data_compare.values : 0;

                                var index = dashboardId+element.id;

                                multipleCharts[index].data.labels = data_labels;
                                multipleCharts[index].data.datasets[0].data = data_values;
                                multipleCharts[index].data.datasets[1].data = data_compare_values;

                                tooltip = {
                                    callbacks: {
                                        title: function(tooltipItem){
                                            if(tooltipItem[0].datasetIndex === 1 && ['line', 'bar'].includes(element.type)){
                                                return data_compare_labels[tooltipItem[0].dataIndex];
                                            } else {
                                                return tooltipItem[0].label;
                                            }
                                        },
                                    }
                                }

                                Object.assign(
                                    multipleCharts[index].options.plugins.tooltip,
                                    tooltip
                                );

                                multipleCharts[index].update();
                            }
                        });
                    },
                    error: function(response){
                        console.log(response);
                    }
                });
            }

            $('.drag-item').bind('dragend', function(){
                var dashboard_id = $(this).parent().data('dashboard-id');
                setTimeout(function(){
                    saveWidgetPositions(dashboard_id);
                }, 3000);
            });
        });

        function saveWidgetPositions (dashboard_id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var itemWidgets = [];

            $('.parent-dashboard-'+dashboard_id).each(function(index){
                itemWidgets[index] = $(this).data('id');
            });

            $.ajax({
                url: "{{ route('admin.homepage.widget-reorder') }}",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: CSRF_TOKEN,
                    _method: "PUT",
                    uid : "{{ auth()->user()->password .''. auth()->user()->id }}",
                    itemWidgets: itemWidgets,
                    dashboardId : dashboard_id,
                },
                success: function (response) {
                    console.log('Widget reorder OK!');
                },
                error: function(response){
                    console.log(response);
                }
            });
        }

        function getWidgetOptions(widget_id, widget_type, dashboard_id) {
            document.getElementById('widget_id_data').value = widget_id;
            document.getElementById('widget_id_data_notes').value = widget_id;
            document.getElementById('dashboard_id_data').value = dashboard_id;

            if(widget_type != 'kpi'){
                document.getElementById('select2').style.display = 'none';
                document.getElementById('select3').style.display = 'none';
                document.getElementById('prefix').style.display = 'none';
                document.getElementById('suffix').style.display = 'none';
            } else {
                document.getElementById('select2').style.display = '';
                document.getElementById('select3').style.display = '';
                document.getElementById('prefix').style.display = '';
                document.getElementById('suffix').style.display = '';
            }

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('admin.homepage.get-widget-options') }}",
                type: 'get',
                dataType: 'json',
                data: {
                    _token: CSRF_TOKEN,
                    uid : "{{ auth()->user()->password .''. auth()->user()->id }}",
                    widget_id : widget_id,
                    dashboard_id : dashboard_id,
                },
                success: function (response) {
                    var options;
                    var notes;
                    var ionIcon;
                    var backgroundColorIcon;
                    var prefixDivider;
                    var decimals;
                    var freeFieldPrefix;
                    var freeFieldSuffix;
                    var widgetName;

                    if(response.options){
                        options = JSON.parse(response.options);
                        ionIcon = options.ionIcon?? null;
                        backgroundColorIcon = options.backgroundColorIcon?? 0;
                        prefixDivider = options.prefixDivider?? 0;
                        decimals = options.decimals?? 0;
                        freeFieldPrefix = options.freeFieldPrefix?? '';
                        freeFieldSuffix = options.freeFieldSuffix?? '';
                        widgetName = options.title;
                    } else {
                        ionIcon = null;
                        backgroundColorIcon = 0;
                        prefixDivider = 0;
                        decimals = 0;
                        freeFieldPrefix = '';
                        freeFieldSuffix = '';
                    }

                    if(ionIcon){
                        document.getElementById(options.ionIcon).checked = true;
                    }

                    document.getElementById('select1').value = backgroundColorIcon;
                    document.getElementById('select2').value = prefixDivider;
                    document.getElementById('select3').value = decimals;
                    document.getElementById('prefix').value = freeFieldPrefix;
                    document.getElementById('suffix').value = freeFieldSuffix;
                    document.getElementById('widget-name').value = widgetName?? response.name;

                    if(response.notes){
                        notes = JSON.parse(response.notes);

                        var single_note = '';

                        notes.forEach(function (element) {
                            single_note = single_note + `<li id="i-delete-${element.id}" class="list-group-item">
                                                <span id="note-date">
                                                    <i>${moment(element.created_at).format('L')}</i>
                                                </span>
                                                ${element.notes}
                                                    <a href="#current-pane" onclick="deleteNote(${element.id})" class="close" data-dismiss="alert" aria-label="close" id="hide-${element.id}">&times;</a>
                                                </a>
                                            </li>`;
                        });

                        document.getElementById('wrap-note').innerHTML = single_note;
                    }
                },
                error: function(response){
                    console.log(response);
                }
            });
        }

        function deleteNote(widget_id) {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $(`#hide-${widget_id}`).hide();
            $(`#i-delete-${widget_id}`).hide();

            $.ajax({
                url: "/notes/"+widget_id,
                dataType: "JSON",
                type: 'POST',
                data: {
                    '_token': CSRF_TOKEN,
                    '_method': 'DELETE',
                },
                success: function (response) {
                    console.log(response);
                },
                error: function(response){
                    console.log(response);
                }
            });
        }
        @foreach ($userDashboards as $dashboard)
            $(document).ready(function() {
                var element = document.getElementById("{{ $dashboard->name_slugged }}");
                element.classList.add("active");
            });
        @break
        @endforeach
</script>
@stop
