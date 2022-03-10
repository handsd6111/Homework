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

require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");

for ($i = 0; $i < 50; $i++) {
    

    $phone = "";
    if($i < 10) $phone = "090000000$i";
    else $phone = "09000000$i";
    $member = new MemberModel(
        "testbot$i",
        "asdf",
        "testbot$i",
        "測試市測試區測試路 $i 號",
        $phone,
        2
    );
    $pswRepeat = "asdf";

    $member->Data_Null_Or_NotSet_All();

    /* 後端檢測區 */
    if ($member->Data_Exists("account")) die("帳號已存在");
    if ($member->Data_Exists("phone")) die("電話重複");
    if ($member->Data_Exists("name")) die("名稱已存在");
    if ($member->Get_Data("password") != $pswRepeat) die("密碼不一致");
    if (!preg_match("/^09[0-9]{8}$/", $member->Get_Data("phone"))) die("手機格式為09xxxxxxxx");
    /*------------------*/

    $member->Add_New_Member();
}
