@extends('layouts.admin')

@section('title', $role::getMsgTrans('create_heading'))

@section('content')
@include('partials._content-heading', ['title' => $role::getMsgTrans('create_heading')])

@include('partials._alerts')

{{ html()->form('POST', route('admin.roles.store'))->class('')->open() }}

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            @include('partials.inputs._text', ['name' => 'name', 'label' => $role::getAttrsTrans('name').'*'])
        </div>
        <div class="row">
            <div class="col-md-12 form-group @if($errors->has('level')) has-error @endif">
                {{ html()->label($role::getAttrsTrans('level'), 'level')->class('control-label') }}
                {{ html()->number('level', 1)->class('form-control') }}
                @include('partials._field-error', ['field' => 'level'])
            </div>
        </div>
        <div class="row">
            @include('partials.inputs._select-multi', ['name' => 'sub_roles', 'label' => $role::getAttrsTrans('sub_roles'), 'options' => [], 'multiple' => true])
        </div>
    </div>
</div>

<div class="pull-left">
    @include('partials._submit-crud-form-action-btn')
</div>

<div class="pull-right">
    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">@lang('Back')</a>
</div>
{{ html()->form()->close() }}
@stop

@section('javascript')
    @parent

    <script type="text/javascript">
        let roles = {!! json_encode($roles) !!};
        loadSubRoles(1);

        $('#level').change(function () {
            loadSubRoles($(this).val());
        })

        function loadSubRoles(level) {
            $('#sub_roles option').remove();
            $(roles).each(function () {
                if (this.level > level) {
                    $('#sub_roles').append('<option value="' + this.id + '">' + this.role_name + '</option>')
                }
            })
        }
    </script>
@endsection

