window.Vue = require('vue');
Vue.prototype._t = window._t;
window.Event = new Vue();
import Error from './classes/Error';
window.Error = Error;

window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');
    require('jquery-ui-dist/jquery-ui');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.response.use(null, function(err) {
    if(err.response.status == 401 || err.response.statusText == 'Unauthorized') {
        document.location.href="/";
    }

    if(err.response.status == 403) {
        if(err.response.hasOwnProperty('data')
            && err.response.data.hasOwnProperty('redirect_to')
        ) {
            document.location.href= err.response.data.redirect_to;
        }
    }

    return Promise.reject(err);
});

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = window.token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
require('jquery-slimscroll');

window.Swal = require('sweetalert2');

window.moment = require('moment');

require('summernote');
require('summernote/lang/summernote-bg-BG');
require('summernote/lang/summernote-it-IT');

window.WOW = require('wowjs');

require('bootstrap-daterangepicker');

require('mark.js/dist/jquery.mark');

require('datatables.mark.js/dist/datatables.mark');
require('datatables.net-bs');
require('./vendor/dataTables/dataTables.cellEdit');

require('jquery-slimscroll');

require('jquery-file-download');

require('admin-lte');

require('select2/dist/js/select2.full.js');
require('select2/dist/js/i18n/it');

require('./vendor/iosCheckbox/iosCheckbox');

require('cropper');

require('fullcalendar/dist/fullcalendar');
require('fullcalendar/dist/locale-all');

require('bootstrap-colorselector/dist/bootstrap-colorselector');

require('icheck');

require('slick-carousel');

require('gridstack/dist/gridstack.js');
require('gridstack/dist/gridstack.jQueryUI.js');
