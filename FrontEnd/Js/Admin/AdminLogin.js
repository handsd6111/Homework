var loginSuccess = false;

function AdminLogin() {
    $.ajax({
        url: rootPath + "/Action/Admin/AdminLogin.php",
        method: 'POST',
        data: {
            account: $("#account").val(),
            password: $("#password").val()
        },
        dataType: 'json'
    }).done(function (res) {
        console.log(res);
        let msgBox = $('.error-message');
        msgBox.delay(250).fadeIn(250);
        msgBox.delay(2500).fadeOut(250);
        setTimeout(function () {
            if (loginSuccess == true) {
                loginSuccess = false;
                localStorage.setItem('JWT', res.token);
                window.location.href = rootPath + "/FrontEnd/Page/Admin/AdminIndexPage.html";
            }
        }, 1000);
        if (res.status == "success") {
            msgBox.html("<strong style='color:green;'>恭喜! </strong>");
            loginSuccess = true;
        }
        else {
            msgBox.html("<strong style='color:red;'>錯誤! </strong>");
            loginSuccess = false;
        }
        msgBox.append(res.msg);
        $("#msgToggleBtn").click();
    });
}

// function Login_Success() {
//     if (ajaxSuccess == true) {
//         localStorage.setItem('JWT', result.token);
//         window.location.href = rootPath + "/FrontEnd/Page/Member/ProfilePage.html";
//     }
// }