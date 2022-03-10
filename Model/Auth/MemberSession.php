<?php
class MemberSession
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
    }

    public function CreateAuthSession($key, $auth_key)
    {
        $auth_code = '';
        for ($i = 0; $i < 10; $i++) {
            $auth_code .= chr(rand(65, 90));
        }
        $auth_code .= time();

        $_SESSION[$auth_code] = $key;
        setcookie($auth_key, $auth_code, time() + 1200, "/");
    }

    public function IsMemberLogin($auth_key)
    {
        if (!isset($_COOKIE[$auth_key]) || !isset($_SESSION[$_COOKIE[$auth_key]])) return false;
        else return true;
    }

    public function GetMemberInfoT($value) {
        //echo $value;
        var_dump($value);
        //var_dump($_SESSION);
        echo $_SESSION[$value];
        return $_SESSION[$value];
    }

    public function GetMemberInfo($auth_key)
    {
        return $_SESSION[$_COOKIE[$auth_key]];
    }

    public function DeleteAuthSession($auth_key)
    {
        if (isset($_COOKIE[$auth_key]) && isset($_SESSION[$_COOKIE[$auth_key]])) {
            unset($_SESSION[$_COOKIE[$auth_key]]);
            setcookie($auth_key, "", time() - 600, "/");
        }
    }
}
