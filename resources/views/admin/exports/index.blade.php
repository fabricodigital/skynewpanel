@extends('layouts.admin')

@section('title', App\Models\Admin\Export::getTitleTrans())

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\Export::getTitleTrans()])

    @include('partials._alerts')

    @include('admin.datatables._datatable', [
        'dataTableObject' => $dataTableObject,
        'permissionClass' => \App\Models\Admin\Export::class,
        'routeNamespace' => 'exports',
        'additionalButtons' => [
            [
                'position' => 'right',
                'partial_name' => '_clear-old-exports-btn',
                'variables' => [
                    'clearOldJob' => $clearOldJob
                ]
            ]
        ]
    ])

@stop

@section('javascript')
    @parent

    <script type="text/javascript">
        $(document).ready(function () {
            $('.clear_old_exports-btn').on('click', function (evt) {
                evt.preventDefault();
                let that = $(this);
                let confirmMessage = that.data('confirm_message');

                Swal.fire({
                    text: confirmMessage,
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    cancelButtonText: _t('Cancel'),
                    confirmButtonText: _t('Yes'),
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-default',
                    }
                })
                    .then(function (result) {
                        if(result.value) {
                            let actionUrl = $(that).data('url');
                            let form = $('form');
                            let tokenInput = $('<input type="hidden" name="_token" />')
                            let token = window.token.content

                            form.attr('action', actionUrl);
                            form.attr('method', 'POST');
                            tokenInput.val(token);
                            form.append(tokenInput);
                            form.appendTo('body');
                            form.submit();
                        }
                    })
            })
        });
    </script>
@endsection

