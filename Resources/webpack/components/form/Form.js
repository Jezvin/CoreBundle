let Fileupload = require('./FileUpload');

class Form {

    constructor(view_selector) {
        this.$view = $(view_selector);
        this.init();
    }

    init() {
        this.$view.find('.js-colorpicker').minicolors({
            theme: 'bootstrap'
        });
        this.$view.find('.js-datepicker').datepicker();


        this.$view.find('.js-select2').each((i, e) => {
            this.initSelect2($(e));
        });
        new Fileupload('.js-umbrella-fileupload');
    }

    initSelect2($select) {
        let data_options = $select.data('options');
        let options = data_options ? JSON.parse(Utils.decode_html(data_options)) : [];

        if (options['template']) {
            options['templateResult'] = (state) => {
                if (!state.id) {
                    return state.text;
                } else {
                    return $('<span>' + Utils.decode_html($(state.element).data('template')) + '</span>');
                }
            };
        }

        $select.select2(options);
    }
}

module.exports = Form;