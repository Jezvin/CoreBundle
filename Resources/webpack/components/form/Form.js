let Fileupload = require('./FileUpload');
let Select2 = require('./Select2');

class Form {

    constructor($elt) {
        this.$view = $elt;
        this.init();
    }

    init() {
        this.$view.find('.js-colorpicker').minicolors({
            theme: 'bootstrap'
        });
        this.$view.find('.js-datepicker').datepicker();


        this.$view.find('.js-select2').each((i, e) => {
            new Select2($(e));
        });

        this.$view.find('.js-umbrella-fileupload').each((i, e) => {
            new Fileupload($(e));
        });
    }
}

module.exports = Form;