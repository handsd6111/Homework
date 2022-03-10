<?php
require_once(dirname(__FILE__) . "/../Action/ChatRoomProfile.php");

class Message
{
    private $type; //Request = 0 Or Response = 1
    private $opCode; //Member Info = 0,Member Info List = 1, History Message = 2, Send/Receive User Message = 3, Send/Receive System Message = 4
    private $data; //放一些資料，可以為null。

    public function __construct($type, $opCode, $data = null)
    {
        $this->type = $type;
        $this->opCode = $opCode;
        $this->data = $data;
    }
}


class MessageProcessor
{
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function Get_Respose_Message() {
        $this->Process();
        return $this->message;
    }

    private function Process()
    {
        if ($this->message->type = 0) {
            switch ($this->message->opCode) {
                case 0:
                    $this->message->data = $this->Member_Info();
                    break;
                case 1:
                    $this->message->data = "Add manually";
                    break;
                case 2: 
                    $this->message->data = $this->History_Message(50);
                    break;
            }
            $this->message->type = 1;
        }
    }

    private function Member_Info()
    {
        $url = "http://localhost/Action/ChatRoomProfile.php";
        $ch = curl_init();
        $header[] = "Cache-Control: no-cache";
        $header[] = "Pragma: no-cache";
        $header[] = "Authorization: Bearer " . $this->message->data;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    private function History_Message($count) {
        
        
        // return $result;
    }
}
