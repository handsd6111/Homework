<?php

require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberToken.php");

$member = new MemberModel(
    $_POST['account'],
    $_POST['password']
);

$result = array("status" => "fail");

if ($member->Data_Null_Or_NotSet($member->Get_Data('account'))) {
    $result['msg'] = "帳號為空";
    die(json_encode($result));
}
if ($member->Data_Null_Or_NotSet($member->Get_Data('password'))) {
    $result['msg'] = "密碼為空";
    die(json_encode($result));
}

if ($member->Login_Member()) {
    $result['status'] = "success";
    $result['msg'] = "登入成功";
    $memberToken = new MemberToken();
    $data = ["account" => $member->Get_Data('account')];
    $result['token'] = $memberToken->Create_Token($data);
} else {
    $result['msg'] = '帳號或密碼錯誤';
}

echo json_encode($result);
