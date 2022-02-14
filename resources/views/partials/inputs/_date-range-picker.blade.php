@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $startName = format_input_name_with_dots($startName);
    $endName = format_input_name_with_dots($endName);
    $value = isset($value) ? $value : null;
    $value = old($name) ? old($name) : $value;
    $startValue = isset($startValue) ? $startValue : null;
    $startValue = old($startName) ? old($startName) : $startValue;
    $endValue = isset($endValue) ? $endValue : null;
    $endValue = old($endName) ? old($endName) : $endValue;
    $width = !empty($width) ? $width : 12;
    $disabled = isset($disabled) ? $disabled : false;
    $attributes = isset($attributes) ? $attributes : [];
    $attributes['autocomplete'] = 'off';
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($startName) || $errors->has($endName)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    {{
        html()->text($inputName, $value)
            ->class('form-control date-range-picker' . $cssClass)
            ->disabled($disabled)
            ->attributes($attributes)
            ->id($name)
    }}
    {{ html()->hidden($startName, $startValue)->id($name . '_start') }}
    {{ html()->hidden($endName, $endValue)->id($name . '_end') }}
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $startName])
    @include('partials._field-error', ['field' => $startName])
</div>

