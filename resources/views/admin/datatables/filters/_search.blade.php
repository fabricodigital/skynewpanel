<input type="search"
       name="filter_{{ $name }}"
       id="filter_{{ $tableTarget . '_' . $name }}"
       data-table_target="{{ $tableTarget }}"
       class="datatable__filter datatable__filter--search form-control"
       placeholder="@lang('Search...')"
       data-column_target="{{ $columnTarget }}"
>
