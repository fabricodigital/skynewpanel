<li>
    <b>
        @if(is_numeric($label))
            {{ $label + 1 }}
        @else
            {{ $model::getAttrsTrans($label) ?? __($label) }}
        @endif
    </b>
        @if(is_array($value))
            <ul>
                @foreach($value as $k => $v)
                    @includeFirst(['admin.datatables.partials.revisions.templates._json'], ['label' => $k, 'value' => $v])
                @endforeach
            </ul>
        @else
            {{ $v }}
        @endif
</li>