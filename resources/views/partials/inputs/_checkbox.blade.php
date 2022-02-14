@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $disabled = isset($disabled) ? $disabled : false;
    $value = isset($value) ? $value : null;
    
    $checked = old($name) ? ($value == old($name)) : (isset($checked) ? $checked : false);

    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{
        html()->checkbox($inputName)
            ->value($value)
            ->checked($checked)
            ->class('form-check-input' . $cssClass)
            ->disabled($disabled)
            ->attributes($attributes)
    }}
    {{ html()->label($label, $inputName)->class('form-check-label') }}
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
