{{ config('main.app.credits') }}
<div class="pull-right">
    <a href="{{ route('admin.changelog.index') }}">v{{ get_latest_version() }}</a>
</div>