@extends('layouts.admin')

@section('title', __('Profile'))

@section('content')
@include('partials._content-heading', ['title' => __('Profile')])

@include('partials._alerts')

<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-success">
            <div class="box-body box-profile">
                @php
                $profileImage = $user->getMedia('profile-image');
                @endphp
                {{ html()->img(count($profileImage) ? $profileImage[0]->getUrl() : asset('images/admin-panel/profile-placeholder.png'))->class('profile-user-img img-responsive img-circle') }}

                <h3 class="node-name text-center">
                    <i class="fa fa-circle online-offline text-success"></i>
                    {{ $user->name }}
                </h3>
                <p class="text-muted text-center">{{ implode(', ', app()->make('loggedUserRolesNames') ) }}</p>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="tab-green active"><a href="#about" data-toggle="tab">@lang('About')</a></li>
                @can('link_profiles', \App\Models\Admin\User::class)
                    <li class="tab-green"><a href="#linked-profiles" data-toggle="tab" id="linked-profiles-tab-button">@lang('Linked profiles')</a></li>
                @endcan
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="about" style="padding-top: 10px;">
                    @include('admin.profile.partials.form._about')
                </div>
                @can('link_profiles', \App\Models\Admin\User::class)
                    <div class="tab-pane" id="linked-profiles" style="padding-top: 10px;">
                        @include('admin.profile.partials.form._linked-profiles')
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')
@parent
<script>
    $(document).ready(function () {

        let $profileImage = $('#profile-preview-img');
        let $imageInput = $('input[name="image"]');
        let $updateButton = $('.update_profile_btn');
        let cropperConfig = {
            aspectRatio: 1 / 1,
            autoCropArea: 100,
            minContainerWidth: 138,
            minCanvasWidth: 138,
            dragMode: "none",
            cropBoxResizable: false
        };

        $profileImage.cropper(cropperConfig);

        $imageInput.on('change', function () {
            readURL(this);
        });

        $updateButton.on('click', function (evt) {
            evt.preventDefault();

            let form = $('#profile_form');
            let avatar = $('#profile-preview-img');
            let currentAvatarUrl = '{{ count($profileImage) ? $profileImage[0]->getFullUrl() : asset('images/admin-panel/profile-placeholder.png') }}';

            if (currentAvatarUrl.substr(-avatar.attr("src").length) !== avatar.attr("src")) {
                let cropCanvas = $profileImage.cropper('getCroppedCanvas');
                let cropPNG = cropCanvas.toDataURL("image/png");
                $.ajax('{{ route('admin.profile.avatar', [$user]) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        image: cropPNG,
                    },
                    success: function (res) {
                        form.submit();
                    },
                    error: function (err, a, b) {
                        if (err.hasOwnProperty('responseJSON') && err.responseJSON.hasOwnProperty('errors') && err.responseJSON.errors.hasOwnProperty(image)) {
                            let alert = '<div class="alert alert-danger"><ul class="list-unstyled"><li>' + err.responseJSON.errors.image[0] + '</li></ul></div>';
                            $('.alerts').append(alert);
                            $("html, body").animate({scrollTop: 0}, "slow");
                            return false;
                        }

                        let alert = '<div class="alert alert-danger"><ul class="list-unstyled"><li>' + _t('There was an error while uploading the avatar. Please try with another image or contact the support team.') + '</li></ul></div>';
                        $('.alerts').append(alert);
                        $("html, body").animate({scrollTop: 0}, "slow");
                        return false;

                    }
                });
            } else {
                form.submit();
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $profileImage.attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
                $profileImage.cropper('destroy');

                setTimeout(function () {
                    $profileImage.cropper(cropperConfig);
                }, 1000);
            }
        }
    });
</script>
@stop


