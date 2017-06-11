require('./datatable.scss');

class DataTable {

    constructor($elt, options) {
        this.$view = $elt;
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

        if (this.$toolbar.length) {
            this.$toolbar.on('change', 'select, input[type=checkbox], input[type=radio]', () => {
                this.reload();
            });

            this.$toolbar.on('keyup', 'input[type=text]', () => {
                this.reload();
            })
        }

        if (this.options['rowReorder']) {
            this.table.on('row-reorder', (e, diff, edit) => {
                let changeSet = [];
                for (let i = 0, ien = diff.length; i < ien; i++) {
                    let id = this.table.row(diff[i].node).id();
                    changeSet.push({
                        'id' : id,
                        'old_sequence' : diff[i].oldData,
                        'new_sequence' : diff[i].newData
                    });
                }

                let ajax_url = this.options['rowReorder']['url'];
                let ajax_method = this.options['rowReorder']['type'];

                if (ajax_url) {
                    Api.ajax(ajax_method, ajax_url, {'change_set' : changeSet});
                }
            });
        }
    }

    configureOptions() {
        this.options['ajax']['data'] = (d) => {
            return {...d, ...this.toolbarData()};
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