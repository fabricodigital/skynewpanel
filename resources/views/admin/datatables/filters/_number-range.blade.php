<input type="number"
       name="filter_{{ $name }}_from"
       id="filter_{{ $tableTarget . '_' . $name }}_from"
       data-table_target="{{ $tableTarget }}"
       class="datatable__filter datatable__filter--number-range datatable__filter--number-range_from form-control"
       placeholder="@lang('From...')"
       data-column_target="{{ $columnTarget }}"
>

<input type="number"
       name="filter_{{ $name }}_to"
       id="filter_{{ $name }}_to"
       data-table_target="{{ $tableTarget }}"
       class="datatable__filter datatable__filter--number-range datatable__filter--number-range_to form-control"
       placeholder="@lang('To...')"
       data-column_target="{{ $columnTarget }}"
>

