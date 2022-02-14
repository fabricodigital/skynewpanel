<div class="permissions">
    <div class="div-preloader">
        <div class="div-status"></div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 form-group">
            <label>@lang('Section')</label>
            <select
                    name="permissions_sections_filter"
                    class="select2-rendered permissions__filter permissions__filter--sections"
                    data-placeholder="@lang('Select...')"
            >
                <option value=""></option>
                <option value="all" selected="selected">@lang('All')</option>
                @foreach($sections as $section)
                    @isset($section['children'])
                        <optgroup label="{{ eval_helper($section['label']) }}">
                            @foreach($section['children'] as $sectionChild)
                                <option value="{{ $sectionChild['permission_target'] }}">
                                    {{ eval_helper($sectionChild['label']) }}
                                </option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $section['permission_target'] }}">
                            {{ eval_helper($section['label']) }}
                        </option>
                    @endisset
                @endforeach
            </select>
        </div>

        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12 form-group">
            <label>@lang('Roles')</label>
            <select
                    name="permissions_roles_filter"
                    class="select2-rendered permissions__filter permissions__filter--roles"
                    data-placeholder="@lang('Select...')"
                    multiple="multiple"
            >
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="permissions__table-container"></div>
        </div>
    </div>
</div>


@section('javascript')
@parent
<script>
    $(document).ready(function () {
        let permissionsFilter = $('.permissions__filter');
        let permissionsTableContainer = $('.permissions__table-container');
        let preloader = $('.permissions').find('.div-preloader');
        let route = '{{ route('admin.ajax.permissions.index') }}';
        //var countCheckboxes = 0;
        //var countCheckboxesInitialized = 0;

        permissionsFilter.on('change', function () {
            getPermissionsTableView();
        });

        /*$(document).on('ifCreated', '.permissions__table input[type="checkbox"]', function(event){
            countCheckboxesInitialized++;
            if(countCheckboxes == countCheckboxesInitialized){
                permissionsTableContainer.slideDown();
                preloader.css('display', 'none');
            }
        });*/

        getPermissionsTableView();

        function getPermissionsTableView() {
            //countCheckboxes = 0;
            //countCheckboxesInitialized = 0;
            permissionsTableContainer.slideUp();

            preloader.css('display', 'initial');

            let data = {
                "permissions_sections_filter": $('.permissions__filter--sections').val(),
                "permissions_roles_filter": $('.permissions__filter--roles').val(),
            };
            @isset($user_id)
                data.user_id = '{{ $user_id }}';
            @endisset

            $.ajax({
                method: 'GET',
                url: route,
                data: data,
                success: function (res) {
                    permissionsTableContainer.html(res.view);
                    permissionsTableContainer.slideDown();
                    preloader.css('display', 'none');
                    /*countCheckboxes = $('.permissions__table input[type="checkbox"]').length;
                    if(res.view == ""){
                        preloader.css('display', 'none');
                    }*/
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    });
</script>
@stop
