function Api() {
};

Api.ajax = function (method, url, params, successCb, errorCb, completeCb) {
    console.log('method = ', method, ', url = ', url, ', params = ', params);
    $.ajax({
        url: url,
        method: method,
        data: params,
        success: function (response) {
            Api.handleResponse(response);
            if (successCb) {
                successCb(response);
            }
        },
        error: function (requestObject, error, errorThrown) {
            toastr.error('Internal server error');
            if (errorCb) {
                errorCb(requestObject, error, errorThrown);
            }
        },
        complete: function () {
            if (completeCb) {
                completeCb();
            }
        }
    });
};

Api.GET = function(url, params, successCb, errorCb, completeCb) {
  Api.ajax('GET', url, params, successCb, errorCb, completeCb);
};

Api.POST = function(url, params, successCb, errorCb, completeCb) {
    Api.ajax('POST', url, params, successCb, errorCb, completeCb);
};

Api.handleResponse = function(response) {
    $.each(response, function() {
        var message = this;
        MessageHandler.handle(message);
    });
};