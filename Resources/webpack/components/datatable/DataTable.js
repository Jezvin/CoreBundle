require('./datatable.scss');

class DataTable {

    constructor(view_selector, options) {
        this.$view = $(view_selector);
        this.$table = this.$view.find('.js-umbrella-datatable');
        this.$toolbar = this.$view.find('.js-umbrella-toolbar');

        this.table = null;

        this.options = options;

        this.init();
        this.bind();
    }

    init() {
        this.configureOptions();
        this.table = this.$table.DataTable(this.options);
    }

    bind() {
        let self = this;

        if (this.$toolbar.length) {
            this.$toolbar.on('change', 'select, input[type=checkbox], input[type=radio]', function(e) {
                self.reload();
            });

            this.$toolbar.on('keyup', 'input[type=text]', function(e) {
                self.reload();
            })
        }

        if (this.options['rowReorder']) {
            this.table.on('row-reorder', function (e, diff, edit) {
                let changeSet = [];
                for (let i = 0, ien = diff.length; i < ien; i++) {
                    let id = self.table.row(diff[i].node).id();
                    changeSet.push({
                        'id' : id,
                        'old_sequence' : diff[i].oldData,
                        'new_sequence' : diff[i].newData
                    });
                }

                let ajax_url = self.options['rowReorder']['url'];
                let ajax_method = self.options['rowReorder']['type'];

                if (ajax_url) {
                    Api.ajax(ajax_method, ajax_url, {'change_set' : changeSet});
                }
            });
        }
    }

    configureOptions() {
        let self = this;

        this.options['ajax']['data'] = function (d) {
            return {...d, ...self.toolbarData()};
        };
    }

    toolbarData() {
        return this.$toolbar.length
            ? this.$toolbar.find('form').serializeObject()
            : [];
    }

    reload() {
        this.$table.DataTable().ajax.reload();
    }
}

module.exports = DataTable;