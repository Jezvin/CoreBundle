function Form(view_selector) {
    this.$view = $(view_selector);
    this.bind();
};

Form.prototype = {
    bind: function() {

        this.$view.find('.js-colorpicker').minicolors({
            theme: 'bootstrap'
        });
        this.$view.find('.js-datepicker').datepicker();
        this.$view.find('.js-select2').select2();
        this.$view.find('.js-wysiwyg').wysihtml5();
    }
};