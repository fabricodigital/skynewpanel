<div class="modal fade modal--columns-visibility" id="{{ $modalId }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">@lang('Columns visibility')</h4>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled sortable">
                    @foreach($dataTableColumns as $column)
                        <li>
                            <i class="fa fa-arrows sortable-handle"></i>
                            <input
                                type="checkbox"
                                name="dt_users_cols_visibility"
                                value="{{ $column['data'] }}"
                                class="icheckbox_square dt_col_visibility_filter"
                                data-table_target="{{ $targetTable }}"
                                data-column_target="{{ $column['data'] }}"
                                @if(!isset($column['visible']) || $column['visible'] === true || $column['visible'] === 'true')checked="checked"@endif
                                id="{{ $column['data'] }}"
                            >
                            <label for="{{ $column['data'] }}">
                                <strong>{{ $column['label'] }}</strong>
                            </label>
                        </li>

                    @endforeach

                </ul>

                <div style="margin-top: 10px;">
                    <a href="javascript:void(0)" class="select_all_dt_column_visibility" data-table_target="{{ $targetTable }}">@lang('Select All')</a>
                    /
                    <a href="javascript:void(0)" class="unselect_all_dt_column_visibility" data-table_target="{{ $targetTable }}">@lang('Unselect All')</a>
                </div>

                <div style="margin-top: 15px;">
                    {{ html()->form('POST', route('admin.datatables.post.save_user_view'))->open() }}
                    {{ html()->hidden('target_table', null) }}
                    {{ html()->hidden('table_params', null) }}
                    {{ html()->hidden('view_name', null) }}
                    <button class="btn btn-success btn-block apply_dt_column_visibility" data-table_target="{{ $targetTable }}">@lang('Apply')</button>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@section('javascript')
    @parent
    <script>
        $(document).ready(function () {
            $('#{{ $modalId }} .sortable').sortable({
                axis: "y",
                handle: ".sortable-handle",
                connectWith: ".sortable",
            });
        })
    </script>
@endsection
