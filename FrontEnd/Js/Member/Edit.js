function Edit() {
    data = {
        password: $("#password").val(),
        address: $("#address").val(),
        name: $("#name").val(),
        phone: $("#phone").val(),
        pswRepeat: $('#pswRepeat').val()
    }
    Ajax(rootPath + "/Action/Member/Edit.php", 'POST', data, 'json', Redi_Profile, 1000);
}

function Redi_Profile() {
    if (ajaxSuccess == true)
        window.location.href = rootPath + "/FrontEnd/Page/Member/ProfilePage.html";
}