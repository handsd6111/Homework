
function Admin_Edit_Member(key) {
    data = {
        originAccount: result.memberData.account,
        account: $("#account").val(),
        password: $("#password").val(),
        address: $("#address").val(),
        name: $("#name").val(),
        phone: $("#phone").val(),
        authority: $("#authority").val(),
        pswRepeat: $('#pswRepeat').val(),
        member_key: key
    }

    Ajax(rootPath + "/Action/Admin/AdminEditMember.php", 'POST', data, 'json', Redi_AdminIndex, 1500);

}

function Redi_AdminIndex() {
    if (ajaxSuccess == true)
        location.href = rootPath + '/FrontEnd/Page/Admin/AdminIndexPage.html';
}