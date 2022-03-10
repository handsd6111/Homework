var xmlhttp;

function Ajax(path, data, exeFunc) {
    $_xmlHttpRequest();
    xmlhttp.onreadystatechange = exeFunc;
    //請求成功後
    xmlhttp.open("POST", path, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(data);
}

function $_xmlHttpRequest() {
    if (window.XMLHttpRequest) {
        //  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
        xmlhttp = new XMLHttpRequest();
    } else {
        // IE6, IE5 浏览器执行代码
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
}

