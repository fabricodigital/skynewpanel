require('./admin-panel/main');
require('./app/main');

let app = document.getElementById("app");
if(app) {
    const app = new Vue({
        el: '#app'
    });
}
