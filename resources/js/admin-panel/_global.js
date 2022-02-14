import Global from './classes/global/Global';
import DataTable from './classes/global/DataTable';
import Form from "./classes/global/Form";
import Select2 from './classes/global/Select2';
import FileUpload from './classes/global/FileUpload';
import DatePicker from './classes/global/DatePicker';
import Impersonate from './classes/global/Impersonate';

window.onerror = function errorHandler(msg, url, line) {
    $("#status").fadeOut();
    $("#preloader").delay(1000).fadeOut("slow");
    return false;
};

window.breakpoints = {
    "xs": {"max": 767},
    "sm": {"max": 991},
    "md": {"max": 1199},
    "lg": {"min": 1200}
};

window.breakpoint = function (breakpoint) {
    if ((!breakpoints[breakpoint].min || ($(window).width() >= breakpoints[breakpoint].min)) &&
        (!breakpoints[breakpoint].max || ($(window).width() <= breakpoints[breakpoint].max))) {
        return true;
    }
    return false;
};

window.SwalToast = Swal.mixin({
    toast: true,
    position: 'top',
    customClass: 'swal2-toast',
    showConfirmButton: false,
    timer: 3000
});

moment.locale($("html").attr("lang"));

window.daterangepickerLocale = {
    format: 'DD/MM/YYYY HH:mm',
    applyLabel: _t('Apply'),
    cancelLabel: _t('Cancel'),
    fromLabel: _t('From'),
    toLabel: _t('To'),
    customRangeLabel: _t('Custom'),
    weekLabel: _t('W')
};

$(document).ready(function () {
    let globalObj = new Global();
    let form = new Form();
    let dtObj = new DataTable();
    let select2Obj = new Select2();
    let fileUploadObj = new FileUpload();
    let datePickerObject = new DatePicker();
    let impersonate = new Impersonate();
});



