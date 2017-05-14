App = function() {};

App.bind = function() {
    var $body = $('body');

    // bind xhr link
    $body.on('click', 'a[data-method="xhr"]', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var url = $(this).attr('href');
        var confirm = $(this).attr('data-confirm');

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
    $body.find('a[data-method="xhr"]').css('pointer-events', 'auto');
    
    // bind xhr form
    $body.on('submit', 'form[data-method="xhr"]', function (e) {
        e.preventDefault();
        var $form = $(this);
        Api.ajax($form.attr('method'), $form.attr('action'), $form.serialize());
    });

    $body.find('.js-umbrella-form').each(function() {
        new Form($(this));
    });
};