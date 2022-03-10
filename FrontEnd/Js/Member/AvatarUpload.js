$(document).ready(function () {
    var fileArray;
    $("#avatar_input").on("change", function () {
        fileArray = $(this)[0].files;
        var formData = new FormData();
        for (var i = 0; i < fileArray.length; i++) {
            formData.append("my_file", fileArray[i]);
        }
        //console.log(fileArray);
        $.ajax({
            url: 'http://' + window.location.host + '/Action/Member/AvatarUpload.php', //傳向後臺伺服器檔案
            type: 'POST', //傳遞方法
            data: formData, //傳遞的資料
            dataType: 'json', //傳遞資料的格式
            headers: {
                "Authorization": "Bearer " + localStorage.getItem('JWT')
            },
            async: false, //這是重要的一步，防止重複提交的                   
            cache: false, //設定為false，上傳檔案不需要快取。
            contentType: false, //設定為false,因為是構造的FormData物件,所以這裡設定為false。
            processData: false, //設定為false,因為data值是FormData物件，不需要對資料做處理。

        }).done(function (res) {
            let msgBox = $('.msgBox');
            if (res.status == "success") {
                msgBox.html("<strong style='color:green;'>恭喜! </strong>");
                Get_Avatar();
            } else {
                msgBox.html("<strong style='color:red;'>錯誤! </strong>");
            }
            msgBox.append(res.msg);
            $("#msgToggleBtn").click();
        });
    });
    Get_Avatar();
});

function Get_Avatar() {
    $.ajax({
        url: 'http://' + window.location.host + '/Action/Member/AvatarResult.php', //傳向後臺伺服器檔案
        type: 'GET', //傳遞方法
        headers: {
            "Authorization": "Bearer " + localStorage.getItem('JWT')
        },
        dataType: 'json', //傳遞資料的格式
    }).done(function (res) {
        if (res.status == 'success') {
            $(".avatar").attr('src', 'http://' + window.location.host + '/Img/Avatar/' + res.msg);
        }
    });
}