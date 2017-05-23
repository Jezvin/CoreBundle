function DataTable(view_selector, options) {
    this.$view = $(view_selector);
    this.$table = this.$view.find('.js-umbrella-datatable');
    this.$toolbar = this.$view.find('.js-umbrella-toolbar');

    this.options = options;

    this.init();
    this.bind();
};

DataTable.prototype = {

    init: function () {
        this.configureOptions();
        this.$table.DataTable(this.options);
    },

    bind: function () {
        var self = this;

        if (this.$toolbar.length) {
            this.$toolbar.on('change', 'select', function(e) {
                self.reload();
            });
        }
    },

    configureOptions: function () {
        var self = this;

        this.options['ajax']['data'] = function (d) {
            d['toolbar'] = self.toolbarData();
            return d;
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