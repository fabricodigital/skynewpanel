export default class FileUpload {
    constructor() {
        this.init();
        this.delete();
        this.download();
    }

    init() {
        $('.file-upload').each(function () {
            $(this).on('change', function () {
                var files = $(this).prop("files");
                var attachmentsPreviewContainer = $('.' + $(this).data('preview-container'));
                attachmentsPreviewContainer.empty();
                $.map(files, function (val) {
                    var previewFileItem = '<li class="list-group-item" data-name="' + val.name + '">' + val.name + '</li>';
                    $(attachmentsPreviewContainer).append(previewFileItem);
                });
            });
        });
    }

    delete() {
        $('body').on('click', '.mailbox-attachment-delete-btn', function () {
            let _that = $(this);
            let destroyUrl = $(_that).data('destroy_url');
            let destroy_msg = $(_that).data('destroy_msg');
            Swal.fire({
                text: destroy_msg,
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Delete'),
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-default',
                }
            }).then(function(result) {
                if(result.value) {
                    $.ajax({
                        'url': destroyUrl,
                        'method': 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token.content
                        },
                        success: function (res) {
                            $(_that).parent('li').remove();
                        },
                        error: function (err) {

                        }
                    });
                }
            });
        })
    }

    download() {
        $('body').on('click', '.download-media-btn', function (evt) {
            evt.preventDefault();

            let downloadUrl = $(this).data('download_url');

            $.fileDownload(downloadUrl, {
                data: {
                    _token: window._token
                },
                httpMethod: 'POST',
            });
        })
    }
}
