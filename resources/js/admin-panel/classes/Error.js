export default class Error
{
    /**
     * Display errors text in element using JQuery
     * @returns {Error}
     */
    show() {
        let i;
        for(i in this.errors) {
            $('.' + i + '-error').text(this.errors[i][0]).show();
        }
        return this;
    }

    /**
     * Remove errors text in element using JQuery
     * @param fields Object|null
     * @returns {Error}
     */
    hide(fields) {
        if(fields) {
            let i;
            for(i in fields) {
                $('.' + i + '-error').text('').hide();
            }
        }else {
            $('.ajax-error').text('').hide();
        }

        return this;
    }

    /**
     * @param errors Object
     * @returns {Error}
     */
    set(errors) {
        this.errors = errors;
        return this;
    }

    /**
     * @param errors Object|null
     * @returns {Error}
     */
    remove(errors) {
        if(errors) {
            let i;
            for(i in errors) {
                delete this.errors[i];
            }
        }else{
            this.errors = [];
        }
        return this;
    }
}