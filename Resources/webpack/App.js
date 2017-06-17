// Lib
let $ = require("jquery");
window.jQuery = $;
window.$ = $;
window._ = require("lodash");

require('bootstrap');
require('select2');
window.toastr = require('toastr');
window.swal = require('sweetalert2');

require('bootstrap-datepicker');
require('jquery-minicolors');
require('jquery-ui-sortable-npm');
require('nestedSortable');

require('datatables.net');
require('datatables.net-bs');
require('datatables.net-rowreorder');
require('datatables.net-fixedheader');

// scss
require('./scss/app.scss');

// plugins
require('./plugins/serialize_object_jquery');
require('./plugins/serialize_file_jquery');

// utils
window.Utils = require('./utils/Utils');

// components
window.Api = require('./components/appproxy/Api');
window.DataTable = require('./components/datatable/DataTable');
window.Tree = require('./components/tree/Tree');
window.Form = require('./components/form/Form');

class App {

    static init() {
        $.fn.dataTable.ext.errMode = 'throw';
        App.bind();
    }

    static bind() {
        let $body = $('body');

        // bind popover
        $('[data-toggle="popover"]').popover({
            container: 'body'
        });

        // bind xhr link
        $body.on('click', 'a[data-xhr-href]', (e) => {
            e.stopPropagation();
            e.preventDefault();

            let $target = $(e.currentTarget);
            let url = $target.data('xhr-href');
            let confirm = $target.data('confirm');

            if (confirm) {
                swal({
                    title: confirm,
                    type: "warning",

                    confirmButtonText: "Yes",
                    confirmButtonClass: "btn btn-primary btn-flat",

                    showCancelButton: true,
                    cancelButtonText: "No",
                    cancelButtonClass: "btn btn-default btn-flat"
                }).then(() => {
                    Api.GET(url);
                    swal.close();
                }).catch(swal.noop);
            } else {
                Api.GET(url);
            }

            return false;
        });

        // bind xhr form
        $body.on('submit', 'form[data-xhr-action]', (e) => {
            e.preventDefault();
            let $form = $(e.currentTarget);
            Api.ajax($form.attr('method'), $form.data('xhr-action'), $form.serializeFiles());
        });

        $body.find('.js-umbrella-form').each((i, e) => {
            new Form($(e));
        });
    }
}

window.App = App;