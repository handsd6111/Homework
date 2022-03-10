<?php

require_once(dirname(__FILE__) . "/../../setting.php");
require_once(dirname(__FILE__) . "/../../Link/MysqliToLink.php");


class MemberModel
{
    private $link;
    private $account;
    private $name;
    private $address;
    private $phone;
    private $password;
    private $authority;

    /**
     * 建構子
     */
    public function __construct($account = "", $password = "", $name = "", $address = "", $phone = "", $authority = "")
    {
        $this->account = $account;
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->password = $password;
        $this->authority = $authority;
        $this->link = new MysqliToLink(dbHost, dbUser, dbPassword, dbPort, dbName);
    }



    public function Data_Null_Or_NotSet_All()
    {
        if ($this->Data_Null_Or_NotSet($this->account)) return "帳號為空";
        if ($this->Data_Null_Or_NotSet($this->name)) return "姓名為空";
        if ($this->Data_Null_Or_NotSet($this->address)) return "地址為空";
        if ($this->Data_Null_Or_NotSet($this->phone)) return "電話為空";
        if ($this->Data_Null_Or_NotSet($this->password)) return "密碼為空";
        if ($this->Data_Null_Or_NotSet($this->authority)) return "權限為空";
    }

    /*
     * 新增一個成員 
     */
    public function Add_New_Member()
    {
        if ($this->link->Insert(
            "member",
            "(account, name, address, phone, password)",
            "('$this->account', '$this->name', '$this->address', '$this->phone', '$this->password')"
        )) return true;
        return false;
    }

    public function Login_Member($auth = 2)
    {
        $result = $this->link->Select(
            "member",
            "member_key, password",
            "WHERE account='$this->account' AND authority=$auth"
        );
        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            if ($row->password == $this->password)
                return true;
        }
        return false;
    }

    public function Select_Member_Use_Account($account)
    {
        $result = $this->link->Select(
            "member",
            "member_key ,account, name, address, phone, password, authority",
            "WHERE account='$account'"
        );
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_object())
                $member = $row;
            return $member;
        }
        return false;
    }

    /*Select User Zone */
    public function Select_Member_Use_Id($id)
    {
        $result = $this->link->Select(
            "member",
            "member_key ,account, name, address, phone, password, authority",
            "WHERE member_key=$id"
        );
        if ($result->num_rows > 0)
            while ($row = $result->fetch_object())
                $member = $row;
        return $member;
    }


    public function Select_Member($count = 0, $offset = 0)
    {
        //$memberSession = new MemberSession();
        //if ($memberSession->IsMemberLogin("admin_session_id") == false) die("尚未登入");
        $memberArray = array();
        $result = $this->link->Select(
            "member",
            "member_key ,account, name, address, phone, password, authority, avatar",
            $count == 0 ? "" : "LIMIT $offset, $count"
        );
        if ($result->num_rows > 0)
            while ($row = $result->fetch_object())
                $memberArray[] = $row;
        return $memberArray;
    }

    public function Select_All_Member()
    {
        return $this->Select_Member();
    }

    public function Member_Count()
    {
        $result = $this->link->Select(
            "member",
            "count(member_key)"
        );
        if ($result->num_rows > 0)
            return $result->fetch_array()['count(member_key)'];
    }

    public function Edit_Member_Use_Account($account)
    {
        if ($this->link->Update(
            "member",
            "account='$this->account', 
            password='$this->password', 
            phone='$this->phone', 
            name='$this->name', 
            address='$this->address',
            authority=$this->authority",
            "WHERE account='$account'"
        )) return true;
        return false;
    }

    public function Avatar_Upload($account, $filename)
    {
        if ($this->link->Update(
            "member",
            "avatar='$filename'",
            "WHERE account='$account'"
        )) return '更新成功';
        return '更新失敗 : ' . $this->link->Get_Error_Message();
    }

    /**
     * 
     */
    public function Edit_Member_Use_Id($key)
    {
        if ($this->link->Update(
            "member",
            "account='$this->account', 
            password='$this->password', 
            phone='$this->phone', 
            name='$this->name', 
            address='$this->address',
            authority=$this->authority",
            "WHERE member_key=$key"
        )) return true;
        return false;
    }


    public function Select_Avatar_Use_Account($account)
    {
        $result = $this->link->Select(
            "member",
            "avatar",
            "WHERE account='$account'"
        );
        if ($result == false) return false;
        if ($result->num_rows > 0)
            while ($row = $result->fetch_object())
                $member = $row;
        return $member->avatar;
    }

    public function Avatar_Upload_Use_Account($account, $avatar_path)
    {
        if ($this->link->Update(
            "member",
            "avatar='$avatar_path'",
            "WHERE account='$account'"
        )) return '更新成功';
        return '更新失敗 : ' . $this->link->Get_Error_Message();
    }

    /**
     * 編輯個人資料
     */
    public function Edit_Profile()
    {
        if ($this->link->Update(
            "member",
            "password='$this->password', 
            phone='$this->phone', 
            name='$this->name', 
            address='$this->address',
            authority=2",
            "WHERE account='$this->account'"
        )) return '更新成功';
        return '更新失敗 : ' . $this->link->Get_Error_Message();
    }

    /**
     * 用primary key刪除成員
     */
    public function Delete_Member_Use_Id($key)
    {
        if ($this->link->Delete("member", "WHERE member_key=$key")) return true;
        return false;
    }

    /*
     * 檢測傳入的data是否沒設定或者為空值
     * ，是則中斷，否則設定內部變數為data的值
     */
    public function Data_Null_Or_NotSet($data)
    {
        if (!isset($data) || empty($data))
            return true;
        return false;
    }

    public function Get_Error_Message()
    {
        $this->link->Get_Error_Message();
    }

    /*
     * 檢測SQL中是否已經有此筆資料。
     */
    public function Data_Exists($var, $Ignore = "")
    {
        $result = $this->link->Select("member", "member_key", "WHERE $var='" . $this->$var . "' AND $var !='$Ignore'");
        if ($result->num_rows > 0) return true;
        return false;
    }

    /*
     * 取得單個變數的值
     */
    public function Get_Data($var)
    {
        return $this->$var;
    }
}
