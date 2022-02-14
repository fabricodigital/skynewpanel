export default class Form {

    constructor() {
        this.initWYSIWYGEditor();
        this.iosCheckbox();
        this.submitCRUDFormBtn();
        this.submitFormWithConfirmation();
        this.visibleIf();
        this.disabledIf();
        this.readonlyIf();
    }

    initWYSIWYGEditor() {
        window.editorDefaultConfig = {
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', []],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            lang: $("html").attr("lang") + '-' + $("html").attr("lang").toUpperCase(),
        };
        $('.editor').each(function(index, item) {
            $(this).summernote(window.editorDefaultConfig);
        });
    }

    iosCheckbox() {
        $(".ios-checkbox").iosCheckbox();
    }

    submitCRUDFormBtn() {
        $('body').on('click', '.do-action-and-new-btn', function (evt) {
            evt.preventDefault();
            let $form = $(this).data('form-target')
                ? $($(this).data('form-target'))
                : $(this).closest('form');

            if(!$form.length) {
                return;
            }

            $form.append('<input type="hidden" name="do_action_and_new" value="1"/>');
            $form.submit();
        })
    }

    submitFormWithConfirmation() {
        $('body').on('click', '.submit_btn_with_confirmation', function (evt) {
            evt.preventDefault();

            let confirmMessage = $(this).data('confirm_message');
            let form = $(this).closest('form');
            Swal.fire({
                text: confirmMessage,
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Yes'),
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-default',
                }
            })
                .then(function (result) {
                    if(result.value) {
                        form.submit();
                    }
                });
        });
    }

    visibleIf() {
        let existCallback = function(element) {
            element.show();
        }
        let missingCallback = function(element) {
            element.hide();
        }
        this.selectorIf('visible-if', existCallback, missingCallback);
    }

    disabledIf() {
        let existCallback = function(element) {
            element.attr('disabled', true);
        }
        let missingCallback = function(element) {
            element.attr('disabled', false);
        }
        this.selectorIf('disabled-if', existCallback, missingCallback);
    }

    readonlyIf() {
        let existCallback = function(element) {
            element.attr('readonly', true);
        }
        let missingCallback = function(element) {
            element.attr('readonly', false);
        }
        this.selectorIf('readonly-if', existCallback, missingCallback);
    }

    selectorIf(cssClass, existCallback, missingCallback) {
        let _class = this;
        $('.' + cssClass).each(function () {
            let element = $(this);
            let targetValue = element.data('target-value');
            let targetElement = $('[name="' + element.data('target-name') + '"]');
            let targetValueSelector = '[name="' + element.data('target-name') + '"]';
            if (targetElement.length == 0) {
                return ;
            }

            if ($(targetElement[0]).is(':checkbox') || $(targetElement[0]).is(':radio')) {
                targetValueSelector += ':checked';
            }

            _class.compareIf($(targetValueSelector).val(), targetValue, element, existCallback, missingCallback);

            targetElement.change(function () {
                _class.compareIf($(targetValueSelector).val(), targetValue, element, existCallback, missingCallback);
            })
        });
    }

    compareIf(currentValue, targetValue, element, existCallback, missingCallback) {
        let exist = false;
        if (Array.isArray(targetValue)) {
            if (Array.isArray(currentValue)) {
                for (let i = 0; i < currentValue.length; i++) {
                    for(let j = 0; j < targetValue.length; j++) {
                        if (targetValue[j] == currentValue[i]) {
                            exist = true;
                            break;
                        }
                    }
                    if (exist == true) {
                        break;
                    }
                }
            } else {
                for(let j = 0; j < targetValue.length; j++) {
                    if (targetValue[j] == currentValue) {
                        exist = true;
                        break;
                    }
                }
            }
        } else {
            if (Array.isArray(currentValue)) {
                for (let i = 0; i < currentValue.length; i++) {
                    if (targetValue == currentValue[i]) {
                        exist = true;
                        break;
                    }
                }
            } else {
                if (targetValue == currentValue) {
                    exist = true;
                }
            }
        }

        if (exist) {
            existCallback(element);
        } else {
            missingCallback(element);
        }
    }
}
