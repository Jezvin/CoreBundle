require('./FileUpload.scss');

class FileUpload {

    constructor(view_selector) {
        this.$view = $(view_selector);

        this.$inputFile = this.$view.find('input[type="file"]');
        this.$inputTxt = this.$view.find('input[type="text"]');

        this.$removeBtn = this.$view.find('.js-umbrella-remove');
        this.$browseBtn = this.$view.find('.js-umbrella-browse');

        this.init();
        this.bind();
    }

    init() {
        this.$removeBtn.hide();
    }

    bind() {
        this.$view.on('click', '.js-umbrella-browse', () => {
           this.$inputFile.click();
        });

        this.$view.on('change', 'input[type="file"]', () => {
            this.refresh();
        });

        this.$view.on('click', '.js-umbrella-remove', () => {
           this.clear();
        });
    }

    clear() {
        console.log('clear');
        this.$inputFile.replaceWith(this.$inputFile.val('').clone(true));
        this.$inputFile = this.$view.find('input[type="file"]');
        this.refresh();
    }

    refresh() {
        let files = this.$inputFile[0].files;
        if (files.length > 0) {
            console.log('refresh => has file');
            let file = files[0];
            this.$inputTxt.val(file.name + ' (' + Utils.bytes_to_size(file.size) + ')');
            this.$removeBtn.show();
        } else {
            console.log('refresh => no file');
            this.$inputTxt.val('');
            this.$removeBtn.hide();
        }
    }

}

module.exports = FileUpload;