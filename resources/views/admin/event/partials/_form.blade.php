<div class="modal fade bs-example-modal-lg" id="event-form-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                {{ html()->form('POST', '/')->id('event-form-modal__form')->acceptsFiles()->novalidate()->open() }}
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {{ html()->label(\App\Models\Admin\Event::getAttrsTrans('title').'*', 'title')->class('control-label') }}
                            {{ html()->text('title', null)->class('form-control')->attributes(['placeholder' => '']) }}
                            <p class="text-danger title-error ajax-error"></p>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        {{ html()->label(__('duration-form-label').'*', 'duration')->class('control-label') }}
                        {{ html()->text('duration', null)->class('form-control')->attributes([]) }}
                        {{ html()->hidden('start', null)->attributes(['title' => \App\Models\Admin\Event::getAttrsTrans('start')]) }}
                        {{ html()->hidden('end', null)->attributes(['title' => \App\Models\Admin\Event::getAttrsTrans('end')]) }}
                        <p class="text-danger start-error ajax-error"></p>
                        <p class="text-danger end-error ajax-error"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        {{ html()->label(\App\Models\Admin\Event::getAttrsTrans('color').'*', 'color')->class('control-label') }}
                        <div class="input-group">
                            {{ html()->text('color', '#3c8dbc')->class('form-control')->attributes(['readonly' => 'true']) }}
                            <div class="input-group-addon">
                                <select id="color-selector">
                                    <option value="#3c8dbc" data-color="#3c8dbc" selected="selected">#3c8dbc</option>
                                    <option value="#00c0ef" data-color="#00c0ef">#00c0ef</option>
                                    <option value="#39CCCC" data-color="#39CCCC">#39CCCC</option>
                                    <option value="#00a65a" data-color="#00a65a">#00a65a</option>
                                    <option value="#f39c12" data-color="#f39c12">#f39c12</option>
                                    <option value="#d33724" data-color="#d33724">#d33724</option>
                                    <option value="#d2d6de" data-color="#d2d6de">#d2d6de</option>
                                    <option value="#605ca8" data-color="#605ca8">#605ca8</option>
                                    <option value="#D81B60" data-color="#D81B60">#D81B60</option>
                                    <option value="#111111" data-color="#111111">#111111</option>
                                </select>
                            </div>
                        </div>
                        <p class="text-danger color-error ajax-error"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 form-group">
                        <legend>@lang('Visibility')</legend>
                    </div>
                    <div class="col-md-6 form-group">
                        {{ html()->label(\App\Models\Admin\Event::getAttrsTrans('roles'), 'roles')->class('control-label') }}
                        {{ html()->multiselect('roles[]')->id('roles')->class('form-control select2-rendered')->data('placeholder', __('Select...')) }}
                        <p class="text-danger roles-error ajax-error"></p>
                    </div>

                    <div class="col-md-6 form-group">
                        {{ html()->label(\App\Models\Admin\Event::getAttrsTrans('users'), 'users')->class('control-label') }}
                        {{
                            html()->multiselect('users[]')
                            ->id('users')
                            ->class('form-control select2-ajax')
                            ->data('placeholder', __('Search...'))
                            ->data('url', route('admin.ajax.users'))
                            ->data('text_field', 'text')
                        }}
                        <p class="text-danger users-error ajax-error"></p>
                    </div>
                    <div class="col-xs-12 form-group">
                        <hr/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {{ html()->label(\App\Models\Admin\Event::getAttrsTrans('description'), 'description')->class('control-label') }}
                        {{ html()->textarea('description')->class('form-control calendar-editor') }}
                        <p class="text-danger description-error ajax-error"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 form-group">
                        <ul class="list-group preview-container attachments-preview-container">

                        </ul>
                        @include('partials.inputs._file-upload', ['name' => 'attachments[]', 'label' => __('Attachments'), 'previewContainer' => 'attachments-preview-container', 'multiple' => true])
                    </div>
                </div>

                <div class="row event-attachments-container">

                </div>

                {{ html()->form()->close() }}
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    <button type="button" class="btn btn-success" id="event_action_button"></button>
                </div>
                <button type="button" class="btn btn-danger" id="event_delete_button">@lang('Delete')</button>
            </div>

            <div class="modal-revisions">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
