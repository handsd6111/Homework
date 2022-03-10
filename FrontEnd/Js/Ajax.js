var ajaxSuccess = false;
var token = null;
var result;
var msgBox;

function Ajax(url, method, data, dataType, onceFunction = null, onceFunctionDelay = 3000, closeBtnFunction = null, showModal = true) {
    var headers;
    if (token != null)
        headers = {
            "Authorization": "Bearer " + token
        };
    else
        headers = null;

    $.ajax({
        url: url,
        method: method,
        headers: headers,
        data: data,
        dataType: dataType
    }).done(function (res) {
        //console.log(res);
        msgBox = $('.msgBox');
        if (res.status == "success") {
            msgBox.html("<strong style='color:green;'>恭喜! </strong>");
            ajaxSuccess = true;
        }
        else if (res.status == "fail") {
            msgBox.html("<strong style='color:red;'>錯誤! </strong>");
            ajaxSuccess = false;
        }
        msgBox.append(res.msg);
        result = res;
        if (showModal == true)
            $("#msgToggleBtn").click();
        setTimeout(() => {
            if (onceFunction != null)
                onceFunction();
        }, onceFunctionDelay);
        $('close-btn').click(function () {
            if (closeBtnFunction != null)
                closeBtnFunction();
        });
    });
}