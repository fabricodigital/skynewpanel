@php
    $attributes = isset($attributes) ? $attributes : [];
    $cssClass = isset($cssClass) ? ' ' . $cssClass : '';
@endphp
<div class="btn btn-default btn-file">
    <i class="fa fa-paperclip"></i> {{ $label }}
    <input
        @if(isset($disabled) && $disabled === true) disabled @endif
        type="file"
        name="{{ $name }}"
        class="btn file-upload{{ $cssClass }}"
        @if($multiple) multiple="multiple" @endif
        data-preview-container="{{ $previewContainer }}"
        @foreach($attributes as $attr => $val)
            {{ ' ' . $attr .'=' . $val}}
        @endforeach
    >
</div>
