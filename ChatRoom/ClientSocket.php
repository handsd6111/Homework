<?php

class ClientSocket
{
    private $socket;
    private $jwt;
    private $memberInfo;

    public function Set_Socket($socket)
    {
        $this->socket = $socket;
    }

    public function Get_Socket()
    {
        return $this->socket;
    }

    public function Set_Member_Info(array $memberInfo)
    {
        $this->memberInfo = $memberInfo;
    }

    public function Get_Member_Info()
    {
        return $this->memberInfo;
    }

    public function Set_JWT(string $jwt)
    {
        $this->jwt = $jwt;
    }

    public function Get_JWT()
    {
        return $this->jwt;
    }

    public function Compare_Socket($socket)
    {
        return $this->socket == $socket;
    }

    public function Compare_JWT($jwt)
    {
        if ($this->jwt == $jwt)
            return true;

        return false;
    }
}
