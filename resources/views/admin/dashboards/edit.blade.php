@extends('layouts.admin')

@section('title', $dashboard::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $dashboard::getMsgTrans('update_heading')])

    @include('partials._alerts')

    {{ html()->modelForm($dashboard, 'PUT', route('admin.dashboards.update', [$dashboard]))->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                @include('partials.inputs._file', [
                    'name' => 'dashboard_image',
                    'label' => __('Browse...').'*',
                    'width' => 6,
                    'previewContainer' =>
                    'img_box-preview-container'
                ])

                @include('partials._attachments', [
                    'item' => $dashboard,
                    'collection' => 'dashboard_image',
                    'canMediaBeenDeleted' => false
                ])
            </div>
            <div class="row">
                @include('partials.inputs._select', [
                    'name' => 'role_id',
                    'label' => $dashboard::getAttrsTrans('role').'*',
                    'width' => 6,
                    'options' => $roles,
                ])

                @include('partials.inputs._select', [
                    'name' => 'account_id',
                    'label' => $dashboard::getAttrsTrans('account').'*',
                    'width' => 6,
                    'options' => $accounts,
                ])
            </div>

            <div class="row">
                @include('partials.inputs._text', [
                    'name' => 'name',
                    'label' => $dashboard::getAttrsTrans('name').'*',
                    'width' => 6,
                    'attributes' => ['maxlength' => 25],
                ])

                @include('partials.inputs._text', [
                    'name' => 'description',
                    'label' => $dashboard::getAttrsTrans('description').'*',
                    'width' => 6,
                    'attributes' => ['maxlength' => 230]
                ])
            </div>

            <div class="row">
                <div class="col-md-12">
                    {{ html()->label($dashboard::getAttrsTrans('widgets'))->class('form-check-label') }}
                </div>
                @foreach ($widgets as $widget)
                    <div class="col-md-2">
                        <div class="cb-preview text-center {{ $dashboard->widgets->contains($widget->id) ? 'selected' : '' }}">
                            {{ html()->img(asset('https://fakeimg.pl/170x100/?text='.$widget->name)) }}
                            <br>

                            {{ html()->checkbox('widgets[]')
                                ->value($widget->id)
                                ->checked($dashboard->widgets->contains($widget->id))
                                ->class('form-check-input')
                                ->disabled(false)
                            }}

                            {{ html()->label($widget->name, $widget->name)->class('form-check-label') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._submit-crud-form-action-btn')
    </div>

    <div class="pull-right">
        <a href="{{ route('admin.dashboards.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>
    {{ html()->form()->close() }}
@stop

@section('custom-css')
    <style>
        .cb-preview { border: 3px solid #efefef; padding: 3px; margin-bottom: 20px; }
        .cb-preview.selected { border-color: #000; }
        .cb-preview.selected img { opacity: 100%; }
        .cb-preview img { display: inline-block; max-width: 100%; height: 100px; opacity: 50%; }
        .cb-preview:hover { cursor: pointer; }
    </style>
@endsection

@section('javascript')
    @parent

    <script>
        $(document).ready(function() {
            $('.cb-preview').click(function() {
                $(this).toggleClass('selected');
                var cb = $(this).find('input[type="checkbox"]');
                cb.prop("checked", !cb.prop("checked"));
            });
        });
    </script>
@endsection
