<?php

require_once(dirname(__FIlE__) . "/MessageProcessor.php");
require_once(dirname(__FILE__) . "/ClientSocket.php");

$option = getopt("", array("host:", "port:"));

$host = Exists($option['host']) ? $option['host'] : "127.0.0.1";
// echo $host;
$port = Exists($option['port']) ? $option['port'] : 9000;
// echo "host: $host, port: $port";
$wsServer = new WebSocketServer($host, $port);
$wsServer->Start_Server();


function Exists(&$data)
{
    if (!isset($data) || empty($data)) return false;
    return true;
}

class WebSocketServer
{
    private $host;
    private $port;
    private $null; //any: null的值，可自行設定不同的值來進行判斷
    private $main_socket; //Socket: 主體socket，做accept用。
    private $clients; //Socket[]: 用來存放連進來的client的陣列
    private $clientsInfo;

    /**
     * 建構子: 設定 Socket 的相關參數
     * 
     * @param string $host  伺服器的 IP
     * @param int $port  伺服器的 Port
     * 
     * @return void
     */
    public function __construct(string $host = "127.0.0.1", int $port = 9000)
    {
        $this->host = $host;
        $this->port = $port;
        $this->null = NULL;

        $this->main_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); //AF_INET: 指定IPv4的連接 
        socket_set_option($this->main_socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->main_socket, $this->host, $this->port); //bind
        socket_listen($this->main_socket);
        $this->clients = array($this->main_socket);

        $main_client = new ClientSocket();
        $main_client->Set_Socket($this->main_socket);
        $main_client->Set_Member_Info(array("MainSocket"));
        $this->clientsInfo = array($main_client);

        // echo in_array($this->main_client, $this->clients);
        // var_dump($this->clients);
    }

    public function Start_Server()
    {
        while (true) {
            $changed = $this->clients;

            socket_select($changed, $this->null, $this->null, 0, 10);
            $this->Add_New_Client($changed);
            // var_dump($changed);
            foreach ($changed as $changed_socket) {

                if ($this->Message_Receice_From_Clinet($changed_socket)) break;
                // echo 'a';

                $this->Client_Disconnected($changed_socket);
            }
        }
    }


    private function Add_New_Client(&$changed)
    {
        if (in_array($this->main_socket, $changed)) {
            $socket_new = socket_accept($this->main_socket); //accpet new socket
            $this->clients[] = $socket_new; //add socket to client array

            $new_clientInfo = new ClientSocket();
            $new_clientInfo->Set_Socket($socket_new);
            $this->clientsInfo[] = $new_clientInfo;

            $header = socket_read($socket_new, 1024); //read data sent by the socket
            $this->perform_handshaking($header, $socket_new); //perform websocket handshake
            // echo 'b';
            socket_getpeername($socket_new, $ip); //get ip address of connected socket
            // $response = $this->mask(json_encode(array('type' => 'system', 'message' => $ip . ' connected'))); //prepare json data
            $response = $this->mask(json_encode(new Message(1, 4, "$ip connected")));
            $this->Send_Message_For_All($response); //notify all users about new connection

            //make room for new socket
            $found_socket = array_search($this->main_socket, $changed);
            unset($changed[$found_socket]);
        }
    }

    private function Message_Receice_From_Clinet(&$changed_socket)
    {
        // var_dump($changed_socket);
        if (socket_recv($changed_socket, $buf, 1024, 0) >= 1) {
            $received_text = $this->unmask($buf); //unmask data
            echo $received_text;
            $tst_msg = json_decode($received_text, true); //json decode 
            $user_name = $tst_msg['name']; //sender name
            $user_message = $tst_msg['message']; //message text
            $user_color = $tst_msg['color']; //color

            //prepare data to be sent to client
            $response_text = $this->mask(json_encode(array('type' => 'usermsg', 'name' => $user_name, 'message' => $user_message, 'color' => $user_color)));
            // send_message2(1, $response_text); //send data 
            // $this->Send_Message_For_All($response_text);
            $this->Send_Message_For_Client_Socket($changed_socket, $response_text);
            return true;
        }
        return false;
    }

    private function Client_Disconnected(&$changed_socket)
    {
        $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
        if ($buf === false) { // check disconnected client
            // remove client for $clients array
            $found_socket = array_search($changed_socket, $this->clients);
            socket_getpeername($changed_socket, $ip);
            unset($this->clients[$found_socket]);
            unset($this->clientsInfo[$found_socket]);

            //notify all users about disconnected connection
            $response = $this->mask(json_encode(array('type' => 'system', 'message' => $ip . ' disconnected')));
            $this->Send_Message_For_All($response);
        }
    }

    private function Send_Message_For_All($msg)
    {

        //echo count($this->clients);
        foreach ($this->clients as $changed_socket) {

            // @socket_write($changed_socket, $msg, strlen($msg));
            @socket_write($changed_socket, $msg, strlen($msg));
        }
        return true;
    }

    private function Send_Message_For_Client_Socket($sokcet, $msg)
    {
        @socket_write($sokcet, $msg, strlen($msg));
        return true;
    }

    private function Send_Message_For_Client_Number($num, $msg)
    {
        @socket_write($this->clients[$num], $msg, strlen($msg));
        return true;
    }

    function unmask($text)
    {
        $length = ord($text[1]) & 127;
        if ($length == 126) {
            $masks = substr($text, 4, 4);
            $data = substr($text, 8);
        } elseif ($length == 127) {
            $masks = substr($text, 10, 4);
            $data = substr($text, 14);
        } else {
            $masks = substr($text, 2, 4);
            $data = substr($text, 6);
        }
        $text = "";
        for ($i = 0; $i < strlen($data); ++$i) {
            $text .= $data[$i] ^ $masks[$i % 4];
        }
        return $text;
    }

    //Encode message for transfer to client.
    function mask($text)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);

        if ($length <= 125)
            $header = pack('CC', $b1, $length);
        elseif ($length > 125 && $length < 65536)
            $header = pack('CCn', $b1, 126, $length);
        elseif ($length >= 65536)
            $header = pack('CCNN', $b1, 127, $length);
        return $header . $text;
    }

    //handshake new client.
    function perform_handshaking($receved_header, $client_conn)
    {
        $headers = array();
        $lines = preg_split("/\r\n/", $receved_header);
        foreach ($lines as $line) {
            $line = chop($line);
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $secKey = $headers['Sec-WebSocket-Key'];
        $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
        //hand shaking header
        $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            // "WebSocket-Origin: $host\r\n" .
            // "WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
            "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
        socket_write($client_conn, $upgrade, strlen($upgrade));
    }

    /**
     * 解構子: 關閉Socket
     */
    public function __destruct()
    {
        socket_close($this->main_socket);
    }
}
