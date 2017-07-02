require('./datatable.scss');

class DataTable {

    constructor($elt, options) {
        this.$view = $elt;
        this.$table = this.$view.find('.js-umbrella-datatable');
        this.$toolbarAction = this.$view.find('.js-umbrella-toolbar .umbrella-actions')
        this.$toolbarForm = this.$view.find('.js-umbrella-toolbar form');

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

        // toolbar => filter form
        if (this.$toolbarForm.length) {
            this.$toolbarForm.on('change', 'select, input[type=checkbox], input[type=radio]', () => {
                this.reload();
            });

            this.$toolbarForm.on('keyup', 'input[type=text]', () => {
                this.reload();
            })
        }

        // toolbar => action form
        if (this.$toolbarAction.length) {
            this.$toolbarAction.on('click', '.js-umbrella-action[data-send=table_data]', (e) => {
                let $target = $(e.currentTarget);

                // avoid default action
                e.preventDefault();
                e.stopPropagation();

                // do ajax call and send extra params
                if ($target.data('xhr-href')) {
                    Api.GET($target.data('xhr-href'), this.table.ajax.params());
                } else {
                    window.location.href = $target.attr('href') + '?' + $.param(this.table.ajax.params());
                }
            });

            this.$toolbarAction.on('click', '.js-umbrella-action[data-send=table_select]', (e) => {
                let $target = $(e.currentTarget);

                // avoid default action
                e.preventDefault();
                e.stopPropagation();

                // do ajax call and send extra params
                if ($target.data('xhr-href')) {
                    Api.GET($target.data('xhr-href'), this.selectedRowsIdParams());
                } else {
                    window.location.href = $target.attr('href') + '?' + $.param(this.selectedRowsIdParams());
                }
            });
        }

        // row re-order
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

        // row click
        if (this.options['rowClick']) {
            this.table.on('click', 'tbody tr td:not(.disable-row-click)', (e) => {
                let $tr = $(e.currentTarget).closest('tr');
                let id = $tr.attr('id');
                if (id) {
                    Api.GET(this.options['rowClick']['url'].replace('123456789', id));
                }
            });
        }

        // row select
        this.table.on('change', 'tbody tr td .js-select-row', (e) => {
            let $target = $(e.currentTarget);
            let $tr = $target.closest('tr');
            if ($target.prop('checked')) {
                $tr.addClass('selected');
            } else {
                $tr.removeClass('selected');
            }
        });

    }

    configureOptions() {
        this.options['ajax']['data'] = (d) => {
            // avoid sending unused params
            delete d['columns'];
            delete d['search'];

            return {...d, ...this.toolbarData()};
        };
    }

    toolbarData() {
        return this.$toolbarForm.length
            ? this.$toolbarForm.serializeObject()
            : [];
    }

    reload() {
        this.$table.DataTable().ajax.reload();
    }

    selectedRowsIdParams() {
        let ids = [];
        this.$table.find('tbody tr.selected').each((e, elt) => {
            ids.push($(elt).attr('id'));
        });
        return {'ids': ids};
    }
}

module.exports = DataTable;