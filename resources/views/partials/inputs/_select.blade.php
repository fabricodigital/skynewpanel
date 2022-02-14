@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $options = !empty($options) ? $options : [];
    $value = isset($value) ? $value : null;
    $value = old($name) ? old($name) : $value;
    $disabled = isset($disabled) ? $disabled : false;
    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    {{
        html()->select($inputName, $options, $value)
            ->class('form-control select2-rendered' . $cssClass)
            ->data('placeholder', __('Select...'))
            ->disabled($disabled)
            ->attributes($attributes)
    }}
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
