
function Delete_Member() {
    Ajax(rootPath + "/Action/Admin/AdminDeleteMember.php", 'POST', { member_key: $('#delete-key').text() }, 'json', Reload_Member_List, 0, null, false);
}

function Reload_Member_List() {
    AdminIndex(10, (page - 1) * 10);
    $("#close-modal-btn").click();
}