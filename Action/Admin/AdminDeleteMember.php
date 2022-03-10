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

if ((!isset($_POST['member_key']) || empty($_POST['member_key'])) && $_POST['member_key'] != 0) {
    $result['msg'] = 'Key值錯誤';
    die(json_encode($result));
}

$member_key = $_POST['member_key'];
if ($memberModel->Delete_Member_Use_Id($member_key)) {
    $result['status'] = "success";
    $result['msg'] = '刪除成功';
} else {
    $result['msg'] = "刪除失敗：" . $memberModel->Get_Error_Message();
}
echo json_encode($result);
