@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $value = isset($value) ? $value : null;
    $value = old($name) ? old($name) : $value;
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    {{ html()->textarea($inputName, $value)->class('form-control editor') }}
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
