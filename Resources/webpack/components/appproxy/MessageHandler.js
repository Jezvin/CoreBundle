
class MessageHandler {
    
    static handlers = {

        execute_js(params) {
            eval(params.value);
        },

        redirect(params) {
            window.location = params.value;
        },

        toast(params) {
            toastr[params.level](params.value);
        },

        open_modal(params) {
            let $modal = $(params.value);
            let $opened_modal = $('.js-umbrella-modal.in');

            if ($opened_modal.length) {
                $opened_modal.html($modal.find('.modal-dialog'));
                new Form($opened_modal);
            } else {
                $modal.modal('show');
            }
        },

        close_modal(params) {
            let $opened_modal = $('.js-umbrella-modal.in');
            if ($opened_modal.length) {
                $opened_modal.modal('hide');
            }
        },

        reload_table(params) {
            let id = params.id;
            let $elem = $('#' + id).find('.js-umbrella-datatable');

            if ($elem.length) {
                $elem.DataTable().ajax.reload();
            }
        }
    };
    
    static handle(message) {
        let handler = MessageHandler.handlers[message.action];
        if (!handler) {
            console.error('App message handler : no handler found for message ', message);
        } else {
            handler(message.params);
        }
    }
}

module.exports = MessageHandler;