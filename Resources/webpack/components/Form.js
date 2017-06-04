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
        this.$view.find('.js-select2').select2();
    }
}

module.exports = Form;