<input type="search"
       name="filter_{{ $name }}"
       id="filter_{{ $tableTarget . '_' . $name }}"
       data-table_target="{{ $tableTarget }}"
       class="datatable__filter datatable__filter--datetime-picker form-control"
       placeholder="@lang('Filter...')"
       data-column_target="{{ $columnTarget }}"
       autocomplete="off"
>
