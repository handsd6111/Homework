function Register() {
    var data = {
        account: $("#account").val(),
        password: $("#password").val(),
        address: $("#address").val(),
        name: $("#name").val(),
        phone: $("#phone").val(),
        pswRepeat: $('#pswRepeat').val()
    }
    Ajax(rootPath + "/Action/Member/Register.php", 'POST', data, 'json', Redi_Login, 1000);
}

function Redi_Login() {
    if (ajaxSuccess == true)
        window.location.href = rootPath + "/FrontEnd/Page/Member/LoginPage.html";
}