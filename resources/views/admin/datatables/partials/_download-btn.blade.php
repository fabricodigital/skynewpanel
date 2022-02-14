@can('dt_download', [$row, $downloadAllPermission, $downloadOwnPermission])
    <a href="#" class="btn btn-primary btn-sm download-media-btn" data-download_url="{{ route('admin.ajax.media.download', [$row->media_id]) }}">
        <i class="fa fa-download"></i>
    </a>
@endcan