@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $value = old($name) ?? $value;
    $disabled = isset($disabled) ? $disabled : false;
    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    <div class="radio">
        @foreach($options as $optionValue => $optionLabel)
            <label for="{{ $name . '_' . $optionValue }}" @if(!$loop->first) style="margin-left: 20px;" @endif>
                {{
                    html()
                        ->radio($inputName)
                        ->id($name . '_' . $optionValue)
                        ->value($optionValue)
                        ->checked($value == $optionValue)
                        ->disabled($disabled)
                        ->class($cssClass)
                        ->attributes($attributes)
                }}
                {{ $optionLabel }}
            </label>
        @endforeach
    </div>
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
