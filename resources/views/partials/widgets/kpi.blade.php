<div class="kpi kpi-{{ $widget->id }}">
    <div class="widget-box__title">
        <div class="widget-icons">
            <div class="icon icon-kpi"
                @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                    style="background-color:{{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['backgroundColorIcon'] }}"
                @endif
            >
            <ion-icon
               @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                    name="{{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['ionIcon']?? 'layers-outline' }}"
                @else
                    name="layers-outline"
                @endif
            ></ion-icon>

            </div>
            <strong style="">
                    @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                        {{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['title']?? $widget->name }}
                    @else
                        {{ $widget->name }}
                    @endif
            </strong>
        </div>
        <a href="#" class="button-modal-icon" data-toggle="modal" data-target="#myModal" onClick="getWidgetOptions('{{ $widget->id }}', '{{ $widget->type }}', '{{ $dashboard->id }}')" style="border: 0px solid transparent;background-color: transparent;color: black">
            <ion-icon name="construct-outline"></ion-icon>
        </a>
    </div>
    <div class="widget-box__body kpi-body">
        <div class="kpi-body-content">
            <div>
                <small>
                    @if(optional($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first())->widget_settings)
                        {{ json_decode($widget->dashboardUserConfigs->where('dashboard_id', '=', $dashboard->id)->where('user_id', '=', $user->id)->first()->widget_settings, true)['title']?? $widget->name }}
                    @else
                        {{ $widget->name }}
                    @endif
                </small>
                <strong id="kpi-value-{{ $dashboard->id }}-{{ $widget->id }}" class="kpi-value"></strong>
                <div id="kpi-old-value" class="kpi-old-value">
                    <span id="old-value-{{ $dashboard->id }}-{{ $widget->id }}" class="old-value"></span>
                    <div id="feedback-valore-{{ $dashboard->id }}-{{ $widget->id }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>
