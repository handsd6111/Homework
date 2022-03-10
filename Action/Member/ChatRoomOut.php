<?php 
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberModel.php");
require_once(dirname(__FILE__) . "/../../Model/Auth/MemberToken.php");
$token = $_POST['JWT'];
$memberSession = new MemberSession();
$account = $memberSession->GetMemberInfoT($session_id);
echo $account;
//echo $account;
$member = new MemberModel();
//echo json_encode($member->Select_Member_Use_Account($account, 2));
