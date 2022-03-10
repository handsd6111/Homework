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

$account = $data['account'];
$memberModel = new MemberModel();

$res = $memberModel->Select_Avatar_Use_Account($account);

if ($res != false) {
    $result['status'] = 'success';
    $result['msg'] = $res;
}else {
    $result['msg'] = '取得圖片失敗';
}

echo json_encode($result);
