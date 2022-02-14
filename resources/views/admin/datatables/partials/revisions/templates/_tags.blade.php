<li>
    <b>{{ $model::getAttrsTrans($key) }}:</b>
    @foreach($value as $tag)
        <span class="label label-info">{{ $model::getEnumsTrans($key, $tag) ?? __($tag) }}</span>
    @endforeach
</li>
