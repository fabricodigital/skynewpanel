@php
    $attachments = $item->getMedia(isset($collection) ? $collection : 'attachments');
    $countAttachments = count($attachments);
@endphp
@if($countAttachments > 0)
<div class="box-footer">
    <ul class="mailbox-attachments clearfix">
        @forelse($attachments as $attachment)
            <li>
                @if (!empty($canMediaBeenDeleted) && Auth::user()->can('delete_media', $item))
                    <i class="fa fa-remove mailbox-attachment-delete-btn"
                       data-destroy_url="{{ route('admin.ajax.media.destroy', ['media' => $attachment]) }}"
                       data-destroy_msg="@lang('Are you sure you want to delete') {{ $attachment->file_name }}"
                    ></i>
                @endif
                @switch($attachment->mime_type)
                    @case('application/msword')
                    <span class="mailbox-attachment-icon">
                        <a href="{{ $attachment->getFullUrl() }}" download>
                            <i class="fa fa-file-word-o"></i>
                        </a>
                    </span>

                    <div class="mailbox-attachment-info">
                        <a href="{{ $attachment->getFullUrl() }}" class="mailbox-attachment-name" download>
                            <i class="fa fa-paperclip"></i> {{ $attachment->file_name }}
                        </a>
                    </div>
                    @break
                    @case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                    <span class="mailbox-attachment-icon">
                        <a href="{{ $attachment->getFullUrl() }}" download>
                            <i class="fa fa-file-excel-o"></i>
                        </a>
                    </span>

                    <div class="mailbox-attachment-info">
                        <a href="{{ $attachment->getFullUrl() }}" class="mailbox-attachment-name" download>
                            <i class="fa fa-paperclip"></i> {{ $attachment->file_name }}
                        </a>
                    </div>
                    @break
                    @case('application/pdf')
                    <span class="mailbox-attachment-icon">
                        <a href="{{ $attachment->getFullUrl() }}" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </span>

                    <div class="mailbox-attachment-info">
                        <a href="{{ $attachment->getFullUrl() }}" class="mailbox-attachment-name" target="_blank">
                            <i class="fa fa-paperclip"></i> {{ $attachment->file_name }}
                        </a>
                    </div>
                    @break
                    @default
                    @if(in_array($attachment->mime_type, ['image/png', 'image/jpg', 'image/bmp', 'image/tiff', 'image/gif', 'image/jpeg']))
                        <span class="mailbox-attachment-icon has-img">
                            <a href="{{ $attachment->getFullUrl() }}" target="_blank">
                                <img src="{{ $attachment->getFullUrl() }}" alt="@lang('Attachments')" class="mailbox-attachment-image">
                            </a>
                        </span>

                        <div class="mailbox-attachment-info">
                            <a href="{{ $attachment->getFullUrl() }}" class="mailbox-attachment-name" target="_blank">
                                <i class="fa fa-camera"></i> {{ $attachment->file_name }}
                            </a>
                        </div>
                    @else
                        <span class="mailbox-attachment-icon">
                            <a href="{{ $attachment->getFullUrl() }}" download>
                                <i class="fa fa-file-o"></i>
                            </a>
                        </span>

                        <div class="mailbox-attachment-info">
                            <a href="{{ $attachment->getFullUrl() }}" class="mailbox-attachment-name" download>
                                <i class="fa fa-paperclip"></i> {{ $attachment->file_name }}
                            </a>
                        </div>
                    @endif
                    @break
                @endswitch
            </li>
        @empty

        @endforelse
    </ul>
</div>
@endif
