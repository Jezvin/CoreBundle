function MessageHandler() {
};

MessageHandler.handlers = {

    execute_js: function(params) {
        eval(params.value);
    },

    redirect: function(params) {
        window.location = params.value;
    },

    toast: function(params) {
        toastr[params.level](params.value);
    },

    open_modal: function(params) {
        var $modal = $(params.value);
        var $opened_modal = $('.js-umbrella-modal.in');

        if ($opened_modal.length) {
            $opened_modal.html($modal.find('.modal-dialog'));
            new Form($opened_modal);
        } else {
            $modal.modal('show');
        }
    },

    close_modal: function(params) {
        var $opened_modal = $('.js-umbrella-modal.in');
        if ($opened_modal.length) {
            $opened_modal.modal('hide');
        }
    }
};

MessageHandler.handle = function(message) {
    var handler = MessageHandler.handlers[message.action];
    if (!handler) {
        console.log('App message handler : no handler found for message ', message);
    } else {
        handler(message.params);
    }
};