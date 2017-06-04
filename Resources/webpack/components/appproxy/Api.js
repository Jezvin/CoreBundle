let MessageHandler = require('./MessageHandler');

class Api {

    static ajax(method, url, params, successCb, errorCb, completeCb) {
        console.log('method = ', method, ', url = ', url, ', params = ', params);
        $.ajax({
            url: url,
            method: method,
            data: params,
            success: (response) => {
                Api.handleResponse(response);
                if (successCb) {
                    successCb(response);
                }
            },
            error: (requestObject, error, errorThrown) => {
                toastr.error('Internal server error');
                if (errorCb) {
                    errorCb(requestObject, error, errorThrown);
                }
            },
            complete: () => {
                if (completeCb) {
                    completeCb();
                }
            }
        });
    }

    static GET(url, params, successCb, errorCb, completeCb) {
        Api.ajax('GET', url, params, successCb, errorCb, completeCb);
    }

    static POST(url, params, successCb, errorCb, completeCb) {
        Api.ajax('POST', url, params, successCb, errorCb, completeCb);
    };

    static handleResponse(response) {
        for (const message of response) {
            MessageHandler.handle(message);
        }
    };
}

module.exports = Api;