<?php
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $ip = $_SERVER["HTTP_CLIENT_IP"];
} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
} else {
    $ip = $_SERVER["REMOTE_ADDR"];
}
if ($ip != '::1' && $ip != '127.0.0.1') {
    require_once(dirname(__FILE__) . "/../../Permissions.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Css/Admin/AdminRegister.css">
    <script src="../../Js/jquery-3.6.0.min.js"></script>
    <script src="../../Js/AdminRegister.js" defer="defer"></script>
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="title">註冊</div>
        <form class="admin-register-form">
            <div class="input-item">
                <div class="input-text">帳號*</div>
                <input type="text" name="account" id="account">
            </div>
            <div class="input-item">
                <div class="input-text">姓名*</div>
                <input type="text" name="name" id="name">
            </div>
            <div class="input-item">
                <div class="input-text">地址*</div>
                <input type="text" name="address" id="address">
            </div>
            <div class="input-item">
                <div class="input-text">電話*</div>
                <input type="text" name="phone" id="phone">
            </div>
            <div class="input-item">
                <div class="input-text">密碼*</div>
                <input type="password" name="password" id="password">
            </div>
            <div class="input-item">
                <div class="input-text">二次密碼*</div>
                <input type="password" name="pswRepeat" id="pswRepeat">
            </div>
            <p class="remark-msg">*為必填事項</p>
            <button type="button" onclick="AdminRegister()">註冊</button>
            <div class="error-message"></div>
        </form>
    </div>

    <script>
        console.log($(".input-text"));
        for (let i = 0; i < $("input").length; i++) {
            $("input").eq(i).focus(function() {
                $(".input-text").eq(i).css("top", "0");
                $(".input-text").eq(i).css("color", "#000");
                //if ($("input").eq(i).val() != '') $(".input-text").eq(i).css("top", "0");
                //else $(".input-text").eq(i).css("top", "30px");
            });
            $("input").eq(i).blur(function() {
                //$(".input-text").eq(i).css("top", "0");
                if ($("input").eq(i).val() != '') {
                    $(".input-text").eq(i).css("top", "0");
                    $(".input-text").eq(i).css("color", "#000");
                } else {
                    $(".input-text").eq(i).css("top", "30px");
                    $(".input-text").eq(i).css("color", "#999");
                }
            });
        }
    </script>
</body>

</html>