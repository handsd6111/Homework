<?php
require_once(dirname(__FILE__) . "/../../../Model/Auth/MemberModel.php");

$member = new MemberModel(
    $_POST['account'],
    $_POST['password'],
    $_POST['name'],
    $_POST['address'],
    $_POST['phone'],
    1
);
$pswRepeat = $_POST['pswRepeat'];

$member->Data_Null_Or_NotSet_All();

/* 後端檢測區 */
if ($member->Data_Exists("account")) die("帳號已存在");
if ($member->Data_Exists("phone")) die("電話重複");
if ($member->Data_Exists("name")) die("名稱已存在");
if ($member->Get_Data("password") != $pswRepeat) die("密碼不一致");
if (!preg_match("/^09[0-9]{8}$/", $member->Get_Data("phone"))) die("手機格式為09xxxxxxxx");
/*------------------*/

echo $member->Add_New_Member();