export default class Select2 {

    constructor() {
        this.showHideEvents();
        this.initAjax();
        this.init();
        this.initWithFlag();

        //??????????????????????????
        $(".select2-selection__rendered").removeAttr('title');
        //??????????????????????????
    }

    init() {
        $('.select2-rendered').each(function () {
            let current = $(this);
            current.select2({
                language: $("html").attr("lang"),
                dropdownCssClass: "select2-" + current.attr("name"),
                allowClear: true,
                cache: true,
                debug: true,
                placeholder: current.data('placeholder'),
            });
        });
    }

    initAjax() {
        $(".select2-ajax").each(function () {
            var current = $(this);
            current.css('width', '100% !important');
            current.on('select2:select', function (e) {
                var data = e.params.data;
                if($("[name='" + current.attr("name") + "-label']").length > 0){
                    $("[name='" + current.attr("name") + "-label']").val(data.text);
                }
            });
            current.select2({
                language: $("html").attr("lang"),
                dropdownCssClass: "select2-" + current.attr("name"),
                ajax: {
                    url: function (params) {
                        return current.attr('data-url');
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            needle: params.term, // search term
                        };
                    },
                    processResults: function (data) {
                        var selectobj = $.map(data, function (v) {
                            return {
                                'text': v[current.data('text_field')],
                                'id': v[current.data('id_field')] ? v[current.data('id_field')] : v['id'],
                            }
                        });
                        return { results: selectobj };
                    },
                    cache: true,
                    error: function (err) {

                    }
                },
                placeholder: current.data('placeholder'),
                minimumInputLength: 2,
                allowClear: true
            });
            if($("[name='" + current.attr("name") + "-label']").length > 0){
                $("[name='" + current.attr("name") + "-label']").val(current.text());
            }
        });
    }

    initWithFlag() {
        let select2Obj = this;
        $('.select2-with-flag').each(function () {
            let current = $(this);
            current.select2({
                language: $("html").attr("lang"),
                dropdownCssClass: "select2-" + current.attr("name"),
                allowClear: true,
                cache: true,
                debug: true,
                placeholder: current.data('placeholder'),
                templateResult: select2Obj.addFlag,
                templateSelection: select2Obj.addFlag,
            });
        });
    }

    addFlag(opt) {
        let flag = $(opt.element).data('flag');

        if(!flag) {
            return opt.text;
        }

        return $('<span><i class="flag-icon flag-icon-' + flag + '"></i>' + opt.text + '</span>');
    }

    showHideEvents() {
        if($('[data-show], [data-hide]').length){
            $(document).on("click", '[data-show]', function(){
                let fieldsToShow = $(this).data("show");
                $.each(fieldsToShow, function(i, item){
                    $("#" + item).closest(".form-group").show();
                });
            });
            $("[data-show]:checked").trigger("click");
            $(document).on("click", '[data-hide]', function(){
                let fieldsToHide = $(this).data("hide");
                $.each(fieldsToHide, function(i, item){
                    $("#" + item).closest(".form-group").hide();
                });
            });
            $("[data-hide]:checked").trigger("click");
        }
    }
}
