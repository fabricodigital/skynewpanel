@extends('layouts.admin')

@section('title', App\Models\Admin\User::getMsgTrans('view_heading'))

@section('content')
    @include('partials._content-heading', ['title' => App\Models\Admin\User::getMsgTrans('view_heading'), 'deleted' => !empty($user->deleted_at) ? true : false])

    <div class="panel panel-default{{ !empty($user->deleted_at) ? ' record-deleted' : '' }}">
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>{{ $user::getAttrsTrans('name') }}</th>
                    <td field-key='name'>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>{{ $user::getAttrsTrans('surname') }}</th>
                    <td field-key='name'>{{ $user->surname }}</td>
                </tr>
                <tr>
                    <th>{{ $user::getAttrsTrans('email') }}</th>
                    <td field-key='email'>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>{{ $user::getAttrsTrans('locale') }}</th>
                    <td>
                        <i class="flag-icon flag-icon-{{ $user->locale != 'en' ? $user->locale : 'gb' }}"></i>
                        {{ __(config('main.available_languages')[$user->locale]) }}
                    </td>
                </tr>
                @can('view_sensitive_data', \App\Models\Admin\User::class)
                    <tr>
                        <th>{{ $user::getAttrsTrans('roles') }}</th>
                        <td field-key="roles">
                            @foreach($user->getRolesTrans() as $role)
                                <span class="label label-info">{{ $role->role_name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>{{ $user::getAttrsTrans('state') }}</th>
                        <th field-key="active">
                            @include('admin.users.partials._states', ['state' => $user->state])
                        </th>
                    </tr>
                @endcan
            </table>
        </div>
    </div>

    <div class="pull-left">
        @include('partials._restore-action-btn', ['model' => $user, 'routeNamespace' => 'users'])
    </div>

    <div class="pull-right">
        @include('admin.users.partials._impersonate', ['user' => $user])
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">@lang('Back')</a>
    </div>

    <div class="clearfix"></div>

    @can('view_all', \App\Models\Admin\Revision::class)
        @include('admin.datatables._datatable-secondary', [
            'dataTableObject' => $revisionsDataTableObject,
            'permissionClass' => \App\Models\Admin\Revision::class,
            'title' => \App\Models\Admin\Revision::getTitleTrans(),
            'disableColumnsSelect' => true
        ])
    @endcan

@stop


