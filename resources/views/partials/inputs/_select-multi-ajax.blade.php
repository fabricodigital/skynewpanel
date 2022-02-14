@php
    $inputName = $name;
    $name = format_input_name_with_dots($name);
    $width = !empty($width) ? $width : 12;
    $selected = !empty($selected) ? $selected : [];
    $multiple = !empty($multiple) ? true : false;
    $disabled = isset($disabled) ? $disabled : false;
    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="col-md-{{ $width }} form-group @if($errors->has($name)) has-error @endif">
    {{ html()->label($label, $inputName)->class('control-label') }}
    <select name="{{ $inputName }}[]" id="{{ $name }}"
            class="form-control select2-ajax{{ $cssClass }}"
            data-placeholder="@lang('Search...')"
            data-url="{{ $route }}"
            data-text_field="text"
            @if($disabled) disabled="disabled" @endif
            @if($multiple) multiple="multiple" @endif
            @foreach($attributes as $attr => $val)
                {{ ' ' . $attr .'=' . $val}}
            @endforeach
    >
        @foreach($selected as $id => $optionLabel)
            <option value="{{ $id }}" @if(!old($name) || (old($name) && in_array($id, old($name)))) selected="selected" @endif>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @isset($help)<p class="help-block">{!! $help !!}</p>@endisset
    @include('partials._field-error', ['field' => $name])
</div>
