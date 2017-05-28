function DataTable(view_selector, options) {
    this.$view = $(view_selector);
    this.$table = this.$view.find('.js-umbrella-datatable');
    this.$toolbar = this.$view.find('.js-umbrella-toolbar');

    this.table = null;

    this.options = options;

    this.init();
    this.bind();
};

DataTable.prototype = {

    init: function () {
        this.configureOptions();
        this.table = this.$table.DataTable(this.options);
    },

    bind: function () {
        var self = this;

        if (this.$toolbar.length) {
            this.$toolbar.on('change', 'select', function(e) {
                self.reload();
            });

            this.$toolbar.on('keyup', 'input[type=text]', function(e) {
                self.reload();
            })
        }

        if (this.options['rowReorder']) {
            this.table.on('row-reorder', function (e, diff, edit) {
                for (var i = 0, ien = diff.length; i < ien; i++) {
                    var id = self.table.row(diff[i].node).id();
                    console.log(id + ' updated to be in position ' + diff[i].newData + ' (was ' + diff[i].oldData + ')');
                }
            });
        }
    },

    configureOptions: function () {
        var self = this;

        this.options['ajax']['data'] = function (d) {
            return $.extend({}, d, self.toolbarData());
        };
    },

    toolbarData: function () {
        return this.$toolbar.length
            ? this.$toolbar.find('form').serializeObject()
            : [];
    },

    reload: function() {
        this.$table.DataTable().ajax.reload();
    }
};