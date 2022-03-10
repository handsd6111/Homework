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

//檢查檔案是否上傳成功
if ($_FILES['my_file']['error'] === UPLOAD_ERR_OK) {
    //echo '檔案名稱: ' . $_FILES['my_file']['name'] . '<br/>';
    //echo '檔案類型: ' . $_FILES['my_file']['type'] . '<br/>';
    //echo '檔案大小: ' . ($_FILES['my_file']['size'] / 1024) . ' KB<br/>';
    //echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'] . '<br/>';

    //FF檢查檔案是否已經存在
    if (file_exists('../../Img/Avatar/' . $_FILES['my_file']['name'])) {
        $result['msg'] = "檔案已存在";
        die(json_encode($result));
        //echo '檔案已存在。<br/>';
    } else {
        $file = $_FILES['my_file']['tmp_name'];
        $file_extension = '.' . explode('.', $_FILES['my_file']['name'])[1];
        $dest = '../../Img/Avatar/' . time() . $file_extension; // . '-' . $_FILES['my_file']['name'];

        //將檔案移至指定位置
        move_uploaded_file($file, $dest);
        $memberModel->Avatar_Upload_Use_Account($account, time() . $file_extension);
        $result['status'] = 'success';
        $result['msg'] = "上傳成功";
        die(json_encode($result));
        //echo '上傳成功';
    }
} else {
    $result['msg'] = "錯誤代碼：". $_FILES['my_file']['error'];
    die(json_encode($result));
    //echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
}
