<?php
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");

$member = new MemberModel(
    $_POST['account'],
    $_POST['password'],
    $_POST['name'],
    $_POST['address'],
    $_POST['phone'],
    2
);
$pswRepeat = $_POST['pswRepeat'];

$result = array("status" => "fail");

$result["msg"] = $member->Data_Null_Or_NotSet_All();
if ($result['msg'] != '') die(json_encode($result));

/* 後端檢測區 */
if ($member->Data_Exists("account")) {
    $result["msg"] = "帳號已存在";
    die(json_encode($result));
}
if ($member->Data_Exists("phone")) {
    $result["msg"] = "電話已存在";
    die(json_encode($result));
}
if ($member->Data_Exists("name")) {
    $result["msg"] = "名稱已存在";
    die(json_encode($result));
}
if ($member->Get_Data("password") != $pswRepeat) {
    $result["msg"] = "密碼不一致";
    die(json_encode($result));
}
if (!preg_match("/^09[0-9]{8}$/", $member->Get_Data("phone"))) {
    $result["msg"] = "手機格式為09xxxxxxxx";
    die(json_encode($result));
}
/*------------------*/

if($member->Add_New_Member()) {
    $result["status"] = "success";
    $result["msg"] = "註冊成功";
} else {
    $result["msg"] = "註冊失敗:" . $member->Get_Error_Message();
}
//var_dump($result);
echo json_encode($result);
