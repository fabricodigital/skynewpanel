export default class Impersonate {

    constructor() {
        this.impersonate();
        this.impersonateBack();
    }

    impersonate() {
        let that = this;
        $('body').on('click', '.impersonate_btn', function (evt) {
            evt.preventDefault();

            let confirmMessage = _t('Are you sure you want to login as the selected user?');
            let form = $(this).closest('form');
            let storeSettingsUrl = $(this).data('store_settings_url');
            let userLocalstorageSettings = that.allLocalStorageItems();

            Swal.fire({
                text: confirmMessage,
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: _t('Cancel'),
                confirmButtonText: _t('Yes'),
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-default',
                }
            })
                .then(function (result) {
                    if (result.value) {
                        axios.post(storeSettingsUrl, {
                            settings_key: 'localstorageItems',
                            settings_data: userLocalstorageSettings,
                        }).then((res) => {
                            localStorage.clear();
                            form.submit();
                        }).catch((err) => {

                        });
                    }
                });
        });
    }

    impersonateBack() {
        let that = this;
        $('body').on('click', '.impersonate_back-btn', function (evt) {
            evt.preventDefault();
            let url = $(this).data('url');
            let goToUrl = $(this).attr('href');

            axios.get(url).then((res) => {
                localStorage.clear();
                _.forEach(res.data.settings, function (value, key) {
                    if (_.isArray(value) || _.isObject(value)) {
                        value = JSON.stringify(value);
                    }

                    localStorage.setItem(key, value);
                });

                window.location.href = goToUrl;
            }).catch((err) => {

            });
        });
    }

    allLocalStorageItems() {

        let archive = {};
        let keys = Object.keys(localStorage);
        let i = keys.length;

        while ( i-- ) {
            archive[ keys[i] ] = localStorage.getItem( keys[i] );
        }

        return archive;
    }
}
