@extends('layouts.admin')

@section('content')

<h3 class="page-title"><i class="fa fa-envelope"></i> @yield('title')</h3>

@include('partials._alerts')

<div class="row">

    {{--Sidebar--}}
    <div class="col-md-3 messenger-sidebar">
        @if(Auth::user()->can('create_direct messages', \App\Models\Admin\MessengerTopic::class) || Auth::user()->can('create_help messages', \App\Models\Admin\MessengerTopic::class) )
        <a href="{{ route('admin.messenger.create') }}" class="btn btn-success btn-block margin-bottom">
            @lang('New message')
        </a>
        @endif

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Folders')</h3>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="{{ route('admin.messenger.inbox') }}" class="{{ request()->segment(2) == 'inbox' || request()->segment(2) == '' ? 'active' : '' }}">
                            <i class="fa fa-inbox"></i> @lang('Inbox')
                            <span class="label label-primary pull-right">{{-- count --}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.messenger.outbox') }}"  class="{{ request()->segment(2) == 'outbox' ? 'active' : '' }}">
                            <i class="fa fa-envelope-o"></i> @lang('Outbox')
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('Labels')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="@if(request()->segment(2) == 'outbox') {{ route('admin.messenger.outbox') }}  @else {{ route('admin.messenger.inbox') }} @endif"
                       class="{{ !request('type') ? 'active' : '' }}"><i class="fa fa-refresh"></i> @lang('All')</a>
                </li>
                <li>
                    <a href="@if(request()->segment(2) == 'outbox') {{ route('admin.messenger.outbox', ['type' => 'direct']) }}  @else {{ route('admin.messenger.inbox', ['type' => 'direct']) }} @endif"
                       class="{{ request('type') == 'direct' ? 'active' : '' }}"><i class="fa fa-user text-light-blue"></i> @lang('Direct')</a>
                </li>
                <li>
                    <a href="@if(request()->segment(2) == 'outbox') {{ route('admin.messenger.outbox', ['type' => 'help']) }}  @else {{ route('admin.messenger.inbox', ['type' => 'help']) }} @endif"
                       class="{{ request('type') == 'help' ? 'active' : '' }}"><i class="fa fa-question text-red"></i> @lang('Help')</a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
    </div>


    {{--Main content--}}
    <div class="col-md-9 messenger-content">
        @yield('messenger-content')
    </div>
</div>

@stop
