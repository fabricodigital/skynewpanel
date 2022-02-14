@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $multiple = !empty($multiple) ? true : false;
    $inputName = !empty($multiple) ? $inputName.'[]' : $inputName;
    $disabled = !empty($disabled) ? true : false;
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    <ul class="list-group preview-container {{ $previewContainer }}">

    </ul>
    @include('partials.inputs._file-upload', ['name' => $inputName, 'label' => __('Browse...'), 'previewContainer' => $previewContainer, 'multiple' => $multiple, 'disabled' => $disabled])
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
