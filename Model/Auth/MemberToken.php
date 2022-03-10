<?php

require_once(dirname(__FILE__) . "/../../vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MemberToken
{
    private $secretKey = "testJWT"; //secret密鑰

    /**
     * JWT 內建編碼
     * 
     * @param $key string secret 密鑰
     * @param $tokenData array 要保存的資料
     * @param $expire int 此Token的效期
     * @return string
     */
    private function JWTEncode(string $key, array $tokenData, int $expire)
    {
        $payload = [
            "iat" => time(),
            "nbf" => time(),
            "exp" => $expire,
        ];
        $payload = array_merge($payload, $tokenData);
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    /**
     * JWT 官方解碼
     * 
     * @param $jwt string 需要解密的Token字串
     * @param $key string 編碼時使用的密鑰 
     * @param $leeway int 解密要等待最大的時間
     * @return array
     */
    private function JWTDecode($jwt, $key, $leeway)
    {
        JWT::$leeway = $leeway;
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return (array)$decoded;
    }

    /**
     * 建立Token
     * 
     * @param $userData array 要保存的資料
     */
    public function Create_Token($userData)
    {
        $key = md5($this->secretKey);
        $jwt = $this->JWTEncode($key, $userData, time() + 1800);
        return $jwt;
    }

    /**
     * 取得Token內的資料
     * 
     * @param $token string JWT的Token
     */
    public function Get_Token_Data($token)
    {
        $key = md5($this->secretKey);
        $data = $this->JWTDecode($token, $key, 30);
        return $data;
    }

}

?>