@extends('layouts.admin')

@section('title', __('Translations'))

@section('content')
    @include('partials._content-heading', ['title' => __('Translations')])

    @include('partials._alerts')

    @hasrole('Super Administrator')

        <div class="page-buttons hidden-xs">
            {{ html()->form('POST', route('admin.translations.scan'))->class('')->style('display: inline-block')->open() }}
                {{ html()->submit(__('Scan strings'))->class('btn btn-success')->style('margin-left: 0') }}
            {{ html()->form()->close() }}

            {{ html()->form('POST', route('admin.translations.export'))->class('')->style('display: inline-block')->open() }}
                {{ html()->submit(__('Export'))->class('btn btn-info') }}
            {{ html()->form()->close() }}

            <button type="button"
                    class="btn btn-warning"
                    data-toggle="modal" data-target="#stringsImportModal">
                @lang('Import')
            </button>
        </div>
    @endhasrole


    <div class="box box-default collapsed-box visible-xs">
        <div class="box-header panel-view">
            <h3 class="box-title" data-widget="collapse">@lang('Actions')</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="box-body">
            @hasrole('Super Administrator')
            {{ html()->form('POST', route('admin.translations.scan'))->style('display: block; margin-bottom: 5px')->open() }}
                {{ html()->submit(__('Scan strings'))->class('btn btn-success btn-block') }}
            {{ html()->form()->close() }}

            {{ html()->form('POST', route('admin.translations.export'))->style('display: block; margin-bottom: 5px')->open() }}
                {{ html()->submit(__('Export'))->class('btn btn-info btn-block') }}
            {{ html()->form()->close() }}

            <button type="button"
                    class="btn btn-warning btn-block"
                    data-toggle="modal" data-target="#stringsImportModal">
                @lang('Import')
            </button>
            @endhasrole
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading panel-view">
            @lang('Table view')
        </div>
        <div class="panel-body table-responsive">
            @include('admin.translations.partials._strings-table', ['strings' => $strings, 'langNames' => $langNames, 'langKeys' => $langKeys])
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="stringsImportModal" tabindex="-1" role="dialog" aria-labelledby="stringsImportModalLabel">
        <div class="modal-dialog" role="document">
            {{ html()->form('POST', route('admin.translations.import'))->acceptsFiles()->open() }}

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="stringsImportModalLabel">@lang('Import strings')</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @include('partials.inputs._file', ['name' => 'strings_file', 'label' => __('Browse...'), 'previewContainer' => 'strings_file-preview-container', 'multiple' => false])
                    </div>
                </div>
                <div class="modal-footer">
                    {{ html()->submit(__('Import'))->class('btn btn-success') }}
                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>

@stop

@section('javascript')
    @parent

    <script>
        $(document).ready(function () {
            let languages = {!! json_encode($langKeys)  !!};

            let editableDT = $('#translations-dt').DataTable({
                mark: true,
                scrollX: true,
                pageLength: 25,
                order: [[languages.length+1, 'asc']],
                language: {
                    url: '/js/admin-panel/vendor/dataTables/lang/' + $("html").attr("lang") + '.json'
                }
            });

            let editColumns = [];
            let inputTypes = [];
            let count = 1;
            languages.forEach(function (lang) {
                editColumns.push(count);
                inputTypes.push({column: count, type: 'textarea'});
                count++;
            });

            editableDT.MakeCellsEditable({
                "columns": editColumns,
                "inputTypes": inputTypes,
                "onUpdate": function (updatedCell, updatedRow, oldValue) {
                    let rowData = updatedRow.data();
                    let data = {key: rowData[0]};
                    let lCount = 1;
                    languages.forEach(function (lang) {
                        data[lang] = rowData[lCount];
                        lCount++;
                    });
                    $.ajax({
                        url: '{{ route('admin.ajax.translations.update-string') }}',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            strings: data,
                        },
                        success: function (res) {
                            //console.log(res);
                        },
                        error: function (err) {
                            //console.log(err);
                        }
                    });
                }
            });
        });
    </script>
@stop
