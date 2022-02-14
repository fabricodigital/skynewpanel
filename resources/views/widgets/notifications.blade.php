<latest-notifications
        :get-route="'{{ route('admin.ajax.notifications.latest-notifications') }}'"
        :base-route="'{{ route('admin.notifications.index') }}'"
        :read-notifications="{{ json_encode(Auth::user()->read_notifications ? Auth::user()->read_notifications : []) }}"></latest-notifications>