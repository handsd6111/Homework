<?php
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberToken.php");

$result = array('status' => 'fail', 'msg' => '無效的Token');

if (!isset(apache_request_headers()['Authorization']) || empty(apache_request_headers()['Authorization'])) {
    die(json_encode($result));
}
$token = str_replace('Bearer ', '', apache_request_headers()['Authorization']);

$memberToken = new MemberToken();
$tokenData = $memberToken->Get_Token_Data($token);
$account = $tokenData['account'];

$update = new MemberModel(
    $_POST['account'],
    $_POST['password'],
    $_POST['name'],
    $_POST['address'],
    $_POST['phone'],
    $_POST['authority']
);

$pswRepeat = $_POST['pswRepeat'];


$result = array("status" => "fail");
$member = $update->Select_Member_Use_Account($_POST['originAccount']);
$result["msg"] = $update->Data_Null_Or_NotSet_All();
if ($result['msg'] != '') die(json_encode($result));

/* 後端檢測區 */
if ($update->Data_Exists("account", $_POST['originAccount'])) {
    $result["msg"] = "帳號已存在";
    die(json_encode($result));
}
if ($update->Data_Exists("phone", $member->phone)) {
    $result["msg"] = "電話已存在";
    die(json_encode($result));
}
if ($update->Data_Exists("name", $member->name)) {
    $result["msg"] = "名稱已存在";
    die(json_encode($result));
}
if ($update->Get_Data("password") != $pswRepeat) {
    $result["msg"] = "密碼不一致";
    die(json_encode($result));
}
if (!preg_match("/^09[0-9]{8}$/", $update->Get_Data("phone"))) {
    $result["msg"] = "手機格式為09xxxxxxxx";
    die(json_encode($result));
}
/*------------------*/

if ((!isset($_POST['member_key']) || empty($_POST['member_key'])) && $_POST['member_key'] != 0) {
    $result['msg'] = 'Key值錯誤';
    die(json_encode($result));
}

if ($update->Edit_Member_Use_Id($_POST['member_key'])) {
    $result["status"] = "success";
    $result["msg"] = "更新成功";
} else {
    $result["msg"] = "更新失敗:" . $update->Get_Error_Message();
}

echo json_encode($result);
