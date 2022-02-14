{{ html()->form('POST', route('admin.profile.link-profile'))->class('')->acceptsFiles()->open() }}
<div class="row">
    @include('partials.inputs._email', ['name' => 'link_email', 'label' => $user::getAttrsTrans('email').'*', 'width' => 5])
    @include('partials.inputs._password', ['name' => 'link_password', 'label' => $user::getAttrsTrans('password').'*', 'width' => 5])
    <div class="col-md-2 form-group">
        {{ html()->submit(__('Link'))->class('btn btn-success btn-block')->attribute('style', 'margin-top: 25px;') }}
    </div>
</div>
{{ html()->form()->close() }}
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading panel-view">
                <h3 class="panel-title">@lang('Linked profiles')</h3>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-striped nowrap" id="linked-profile-dt">
                    <thead>
                    <tr>
                        <th style="font-weight: bold;" width="1%">{{ __('Actions') }}</th>
                        <th style="font-weight: bold;">{{ $user::getAttrsTrans('email') }}</th>
                        <th style="font-weight: bold;">{{ $user::getAttrsTrans('name') }}</th>
                        <th style="font-weight: bold;">{{ $user::getAttrsTrans('surname') }}</th>
                        <th style="font-weight: bold;">{{ __('Account name') }}</th>
                        <th style="font-weight: bold;">{{ __('Active') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($linkedProfiles as $link)
                        <tr data-key="{{ $link->user_id . '_' . $link->linked_user_id }}">
                            <td field-key="actions" title="{{ __('Actions') }}" width="1%" align="center">
                                {{ html()->form('DELETE', route('admin.profile.destroy-linked-profile', ['linked_user_id' => $link->linked_user_id]))->class('')->attributes(['style' => 'display: inline-block;'])->open() }}
                                <button class="btn btn-xs btn-danger data-table-delete-linked-user" type="submit"
                                        data-delete_message="@lang('Are you sure you want to delete the selected item?')" title="@lang('Delete')">
                                    <i class="fa fa-remove"></i>
                                </button>
                                {{ html()->form()->close() }}
                            </td>
                            @if ($link->user_id != Auth::id())
                                <td field-key="email" title="{{ $link->user_email }}">{{ $link->user_email }}</td>
                                <td field-key="name" title="{{ $link->user_name }}">{{ $link->user_name }}</td>
                                <td field-key="surname" title="{{ $link->user_surname }}">{{ $link->user_surname }}</td>
                                <td field-key="account_name" title="{{ $link->user_account_name }}">{{ $link->user_account_name }}</td>
                            @else
                                <td field-key="email" title="{{ $link->linked_user_email }}">{{ $link->linked_user_email }}</td>
                                <td field-key="name" title="{{ $link->linked_user_name }}">{{ $link->linked_user_name }}</td>
                                <td field-key="surname" title="{{ $link->linked_user_surname }}">{{ $link->linked_user_surname }}</td>
                                <td field-key="account_name" title="{{ $link->linked_user_account_name }}">{{ $link->linked_user_account_name }}</td>
                            @endif
                            <td field-key="active" title="{{ $link->active ? __('Yes') : __('No') }}">
                                @if($link->active)
                                    <span class="label label-success">@lang('Yes')</span>
                                @else
                                    <span class="label label-danger">@lang('No')</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('javascript')
    @parent

    <script>
        $(document).ready(function () {
            let editableDT = null;
            $('#linked-profiles-tab-button').click(function () {
                if (!editableDT) {
                    editableDT = $('#linked-profile-dt').DataTable({
                        mark: true,
                        scrollX: true,
                        pageLength: 25,
                        order: [[ 1, "asc" ]],
                        language: {
                            url: '/js/admin-panel/vendor/dataTables/lang/' + $("html").attr("lang") + '.json'
                        }
                    });
                }
            });

            $(document).on('click', '.data-table-delete-linked-user', function(evt) {
                evt.preventDefault();
                var form = $(this).parent('form');

                Swal.fire({
                    text: _t('Are you sure you want to delete the selected item?'),
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    cancelButtonText: _t('Cancel'),
                    confirmButtonText: _t('Delete'),
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-default'
                    }
                })
                .then(function(result) {
                    if(result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
