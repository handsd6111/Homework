<?php

require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberToken.php");


$result = array('status' => 'fail', 'msg' => '無效的Token');

if (!isset(apache_request_headers()['Authorization']) || empty(apache_request_headers()['Authorization'])) {
    die(json_encode($result));
}
$token = str_replace('Bearer ', '', apache_request_headers()['Authorization']);

$memberToken = new MemberToken();
try {
    $data = $memberToken->Get_Token_Data($token);
} catch (Exception $ex) {
    $result['msg'] = "過期的Token";
    die(json_encode($result));
}

$memberModel = new MemberModel();


$memberData = $memberModel->Select_Member_Use_Account($data['account']);
if ($memberData->authority != 1) {
    $result['msg'] = '請求失敗';
    die(json_encode($result));
}
// unset($memberData->authority);
// unset($memberData->member_key);
// //unset($memberData->password);
// if ($memberData != false) {
//     $result['status'] = 'success';
//     $result['msg'] = '請求成功';
//     $result['memberData'] = $memberData;
// }

if (!isset($_POST['count']) || empty($_POST['count'])) {
    $result['msg'] = '請求帳號數量為空';
    die(json_encode($result));
}
if ((!isset($_POST['offset']) || empty($_POST['offset'])) && $_POST['offset'] != 0) {
    $result['msg'] = '請求帳號偏移為空';
    die(json_encode($result));
}


if ($memberData != false) {
    $result['status'] = 'success';
    $result['msg'] = '請求成功';
    $result['memberCount'] = $memberModel->Member_Count();
    $result['memberList'] = $memberModel->Select_Member($_POST['count'], $_POST['offset']);
}

echo json_encode($result);
