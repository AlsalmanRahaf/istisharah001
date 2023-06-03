function waitTime(seconds){
    new Promise((resolve) => {
        setTimeout(() => true, seconds * 1000);
    });
}
function ajaxCall(url, data, callType = "POST", beforeSendFunction = null){
    return new Promise(resolve => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: callType,
            url: url,
            data: data,
            beforeSend: typeof beforeSendFunction == "function" ? beforeSendFunction : null,
            success: function( response ) {
                resolve (response);
            },
            error : function (response) {
                resolve(response);
            }
        });
    });
}
function formRequest(url, data, callType = "POST", beforeSendFunction = null){
    return new Promise(resolve => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            processData:false,
            contentType: false,
            type: callType,
            url: url,
            data: data,
            beforeSend: typeof beforeSendFunction == "function" ? beforeSendFunction : null,
            complete: function( response ) {
                resolve (response);
            }
        });
    });
}
export {waitTime, ajaxCall, formRequest};
