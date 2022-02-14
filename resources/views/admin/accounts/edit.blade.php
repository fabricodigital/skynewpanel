@extends('layouts.admin')

@section('title', $account::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $account::getMsgTrans('update_heading')])

    @include('partials._alerts')
    {{ html()->modelForm($account, 'PUT', route('admin.accounts.update', [$account]))->acceptsFiles()->open() }}

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 form-group" style="min-height: 150px;">
                    @include('partials.inputs._file-upload', ['name' => 'logo', 'label' => __('Logo'), 'previewContainer' => 'avatar-preview-container', 'multiple' => false])
                </div>
                <div class="col-md-8">
                    @include('partials._attachments', ['item' => $account, 'collection' => 'logo', 'canMediaBeenDeleted' => true])
                </div>
            </div>
            <div class="row">
                @include('partials.inputs._text', ['name' => 'name', 'label' => $account::getAttrsTrans('name').'*'])
            </div>
            <div class="row">
                @include('partials.inputs._text', ['name' => 'marketplace_id', 'label' => __('Marketplace ID').'*'])
            </div>
            <div class="row">
                <center><a href="#" class="btn btn-primary" style="background-color: #000000;border-color: #000000;"><i class="fa fa-amazon"></i> {{__('TEST ACCOUNT WITH AMAZON')}}</a></center>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    {{ html()->label($account::getAttrsTrans('widgets'))->class('form-check-label') }}
                </div>
            </div>
            <div class="row">
                @foreach ($widgets as $widget)
                    @if ($loop->index > 0 && $loop->index % 6 == 0)
                        </div>
                        <div class="row">
                    @endif
                    <div class="col-md-2">
                        <div class="cb-preview text-center @if ($account->widgets->contains($widget->id)) selected @endif">
                            {{ html()->img(asset('images/admin-panel/charts-placeholder/' . $widget->type . '.png')) }}<br>

                            {{ html()->checkbox('widgets[]')
                                ->value($widget->id)
                                ->checked($account->widgets->contains($widget->id))
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
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-primary">@lang('Back')</a>
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
