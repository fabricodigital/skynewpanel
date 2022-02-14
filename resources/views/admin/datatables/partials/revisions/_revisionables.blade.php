@if(isset($revisionables) && !empty($revisionables))
    <ul class="list-unstyled">
        @foreach($revisionables as $key => $value)
            @php
                $template = class_exists($model) ? $model::getRevisionableTemplate($key) : 'admin.datatables.partials.revisions.templates._default';
            @endphp
            @if(isset($value[0]))
                @includeFirst([$template, 'admin.datatables.partials.revisions.templates._default'], ['label' => $key, 'value' => $value])
            @endif
        @endforeach
    </ul>
@endif
