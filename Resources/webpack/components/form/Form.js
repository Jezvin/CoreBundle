let Fileupload = require('./FileUpload');

class Form {

    constructor(view_selector) {
        this.$view = $(view_selector);
        this.bind();
    }

    bind() {
        this.$view.find('.js-colorpicker').minicolors({
            theme: 'bootstrap'
        });
        this.$view.find('.js-datepicker').datepicker();


        this.$view.find('.js-select2').each((i, e) => {
            let $select = $(e);
            let data_options = $select.data('select2-options');
            let options = [];

            if (data_options) {
                options = JSON.parse(Utils.decode_html(data_options));
            }

            // options['templateResult'] = function formatState (state) {
            //     if (!state.id) { return state.text; }
            //     let $state = $(
            //         '<span><img src="vendor/images/flags/' + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
            //     );
            //     return $state;
            // };

            $select.select2(options);
        });
        new Fileupload('.js-umbrella-fileupload');
    }
}

module.exports = Form;