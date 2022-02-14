@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $rows = !empty($rows) ? $rows : 3;
    $value = isset($value) ? $value : null;
    $value = old($name) ? old($name) : $value;
    $disabled = isset($disabled) ? $disabled : false;
    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    {{
        html()->textarea($inputName, $value)
            ->class('form-control' . $cssClass)
            ->attribute('rows',  $rows)
            ->disabled($disabled)
            ->attributes($attributes)
    }}
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
