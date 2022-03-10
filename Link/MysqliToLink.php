<?php
require_once("IDBLink.php");
class MysqliToLink implements IDBLink
{
    private $connection;
    private $errorMessage;
    public function __construct($host, $user, $password, $port, $db)
    {
        $this->connection = new mysqli($host, $user, $password, '', $port);
        $this->connection->set_charset("utf8");
        if ($this->connection->connect_error) {
            die("連線失敗 :" . $this->connection->connection_error);
        }
        $select_db = $this->connection->select_db($db);
        if (!$select_db) die("選擇資料庫失敗 : " . $this->connection->error);
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    public function Is_Select_Table($table_name)
    {
        if (empty($table_name))
            die("沒有選擇資料表");
    }

    public function Insert($table, $var, $data)
    {
        $this->Is_Select_Table($table);
        $sql = "INSERT INTO $table $var VALUES $data";
        $result = $this->connection->query($sql);

        $this->errorMessage = $this->connection->error;
        return $result;
    }

    public function Select($table, $var, $option = "")
    {
        $this->Is_Select_Table($table);
        $sql = "SELECT $var FROM $table ";
        if ($option) $sql .= $option;
        $result = $this->connection->query($sql);
        return $result;
    }

    public function Update($table, $var, $option)
    {
        $this->Is_Select_Table($table);
        $sql = "UPDATE $table SET $var ";
        if ($option) $sql .= $option;
        $result = $this->connection->query($sql);
        $this->errorMessage = $this->connection->error;
        return $result;
    }

    public function Delete($table, $option)
    {
        $this->Is_Select_Table($table);
        $sql = "DELETE FROM $table ";
        if ($option) $sql .= $option;
        $result = $this->connection->query($sql);
        $this->errorMessage = $this->connection->error;
        return $result;
    }


    public function Get_Error_Message()
    {
        return $this->errorMessage;
    }
}
