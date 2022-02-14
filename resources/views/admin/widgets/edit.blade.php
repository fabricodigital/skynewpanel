@extends('layouts.admin')

@section('title', $widget::getMsgTrans('update_heading'))

@section('content')
    @include('partials._content-heading', ['title' => $widget::getMsgTrans('update_heading')])

    @include('partials._alerts')

    {{ html()->modelForm($widget, 'PUT', route('admin.widgets.update', [$widget]))->open() }}

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                @include('partials.inputs._text', ['name' => 'name', 'label' => $widget::getAttrsTrans('name').'*', 'width' => 4])

                @include('partials.inputs._select', [
                    'name' => 'type',
                    'label' => $widget::getAttrsTrans('type').'*',
                    'width' => 4,
                    'options' => $widget::getEnumsTrans('type'),
                ])

                @include('partials.inputs._select', [
                    'name' => 'width',
                    'label' => $widget::getAttrsTrans('width').'*',
                    'width' => 4,
                    'options' => $widget::getEnumsTrans('width'),
                ])
            </div>

            <div class="row">
                @include('partials.inputs._textarea', ['name' => 'description', 'label' => $widget::getAttrsTrans('description')])
            </div>

            <div class="row">
                @include('partials.inputs._textarea', ['name' => 'query', 'label' => $widget::getAttrsTrans('query').'*'])
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    {{ html()->label($widget::getAttrsTrans('accounts'))->class('form-check-label') }}
                </div>
            </div>
            <div class="row">
                @foreach ($accounts as $account)
                    @if ($loop->index > 0 && $loop->index % 6 == 0)
                        </div>
                        <div class="row">
                    @endif
                    <div class="col-md-2">
                        <div class="cb-preview text-center @if ($widget->accounts->contains($account->id)) selected @endif">
                            {{ html()->img(asset('images/admin-panel/profile-placeholder.png')) }}
                            <br>
                            
                            {{ html()->checkbox('accounts[]')
                                ->value($account->id)
                                ->checked($widget->accounts->contains($account->id))
                                ->class('form-check-input')
                                ->disabled(false)
                            }}

                            {{ html()->label($account->name, $account->name)->class('form-check-label') }}
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
        <a href="{{ route('admin.widgets.index') }}" class="btn btn-primary">@lang('Back')</a>
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