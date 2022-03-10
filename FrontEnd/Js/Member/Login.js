function Login() {
    var data = {
        account: $("#account").val(),
        password: $("#password").val()
    }
    Ajax(rootPath + "/Action/Member/Login.php", 'POST', data, 'json', Login_Success, 1000);
}

function Login_Success() {
    if (ajaxSuccess == true) {
        localStorage.setItem('JWT', result.token);
        window.location.href = rootPath + "/FrontEnd/Page/Member/ProfilePage.html";
    }
}