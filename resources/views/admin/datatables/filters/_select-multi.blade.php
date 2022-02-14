<select name="filter_{{ $name }}"
        id="filter_{{ $tableTarget . '_' . $name }}"
        data-table_target="{{ $tableTarget }}"
        class="datatable__filter datatable__filter--select-multi form-control"
        data-placeholder="@lang('Filter...')"
        data-column_target="{{ $columnTarget }}"
        multiple="multiple"
>
    @foreach($options as $option)
        <option value="{{ $option->value }}" @if($selected && in_array($option->value, $selected)) selected @endif>
            {{ $option->label }}
        </option>
    @endforeach
</select>
