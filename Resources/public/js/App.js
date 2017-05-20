App = function() {};

App.init = function() {
    $.fn.dataTable.ext.errMode = 'throw';
    App.bind();
};

App.bind = function() {
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
};