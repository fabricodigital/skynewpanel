<script>
    window._token = '{{ csrf_token() }}';
    window.routeLogin = "{{ route('login') }}";
</script>

<script>
    @if(config('app.debug') == false)
        $.fn.dataTable.ext.errMode = 'none';
    @endif
</script>

<script>
    $( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
        if(thrownError == 'Unauthorized' || jqxhr.status == 401) {
            window.location.replace("{{ route('login') }}");
        }
    });
</script>

@yield('javascript')
