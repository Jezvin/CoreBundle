// Lib
var $ = require("jquery");
window.jQuery = $;
window.$ = $;

require('bootstrap');
require('select2');
window.toastr = require('toastr');
window.swal = require('sweetalert2');

require('bootstrap-datepicker');
require('jquery-minicolors');

require('datatables.net');
require('datatables.net-bs');
require('datatables.net-rowreorder');
require('datatables.net-fixedheader');

// scss
require('./scss/app.scss');

// plugins
require('./plugins/serialze_object_jquery');

// core
window.Api = require('./appproxy/Api');

// components
window.DataTable = require('./components/DataTable');
window.Form = require('./components/Form');

window.App = {

    init: function () {
        $.fn.dataTable.ext.errMode = 'throw';
        App.bind();
    },

    bind: function() {
        var $body = $('body');

        // bind xhr link
        $body.on('click', 'a[data-xhr-href]', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var url = $(this).data('xhr-href');
            var confirm = $(this).data('confirm');

            if (confirm) {
                swal({
                    title: confirm,
                    type: "warning",

                    confirmButtonText: "Yes",
                    confirmButtonClass: "btn btn-primary btn-flat",

                    showCancelButton: true,
                    cancelButtonText: "No",
                    cancelButtonClass: "btn btn-default btn-flat"
                }).then(function () {
                    Api.GET(url);
                    swal.close();
                }).catch(swal.noop);
            } else {
                Api.GET(url);
            }

            return false;
        });

        // bind xhr form
        $body.on('submit', 'form[data-xhr-action]', function (e) {
            e.preventDefault();
            var $form = $(this);
            Api.ajax($form.attr('method'), $form.data('xhr-action'), $form.serialize());
        });

        $body.find('.js-umbrella-form').each(function() {
            new Form($(this));
        });
    }
};