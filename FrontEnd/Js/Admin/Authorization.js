$(document).ready(function () {
    token = localStorage.getItem('JWT');
    Ajax(rootPath + "/Action/Admin/AdminProfile.php", 'GET', null, 'json', Set_Profile, 50, null, false);
    setTimeout(() => {
        localStorage.removeItem('JWT');
        msgBox.html("<strong style='color:red;'>糟糕! </strong>時間已超過，自動登出。");
        $("#msgToggleBtn").click();
        setTimeout(() => {
            window.location.href = rootPath + '/Frontend/Page/Member/LoginPage.html';
        }, 1500);
    }, (JSON.parse(atob(localStorage.getItem('JWT').split(".")[1])).exp) * 1000 - new Date().getTime());
});