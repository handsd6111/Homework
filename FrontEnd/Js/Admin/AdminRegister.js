var registerSuccess = false;

function Redi_Login() {
    if (registerSuccess == true)
        window.location.href = "../Member/LoginPage.php";
}

function AdminRegister() {
    // var inputs = ['account', 'name', 'address', 'phone', 'password', 'pswRepeat'];
    // inputs.forEach(element => {
    //     var input = $('#' + element);
    // })
    $.ajax({
        url: "../Admin/Out/AdminRegisterOut.php",
        method: 'POST',
        data: {
            account: $("#account").val(),
            password: $("#password").val(),
            address: $("#address").val(),
            name: $("#name").val(),
            phone: $("#phone").val(),
            pswRepeat: $('#pswRepeat').val()
        },
        dataType: 'html'
    }).done(function (res) {
        let msgBox = $('.error-message');
        msgBox.delay(250).fadeIn(250);
        msgBox.delay(2500).fadeOut(250);
        setInterval(function () {
            //if (registerSuccess == true)
                window.location.href = "../Admin/AdminLoginPage.php";
        }, 2000);
        if (res == "註冊成功") {
            msgBox.html("<strong style='color:green;'>恭喜! </strong>");
            registerSuccess = true;
        }
        else {
            msgBox.html("<strong style='color:red;'>錯誤! </strong>");
            registerSuccess = false;
        }
        msgBox.append(res);
        $("#msgToggleBtn").click();
    });
}