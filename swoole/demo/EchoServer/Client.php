<?php
/**
 * Created by PhpStorm.
 * Project    : phpLab
 * File       : Client.php
 * Author     : wangxb
 * Email      : wangxiaobo@parkingwang.com
 * Date       : 2017-05-04 10:54
 * Description:
 */



class Client
{
    private $client;

    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect() {
        if( !$this->client->connect("127.0.0.1", 9501 , 1) ) {
            echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
        }

        fwrite(STDOUT, "请输入消息：");
        $msg = trim(fgets(STDIN));
        $this->client->send( $msg );

        $message = $this->client->recv();
        echo "Get Message From Server:{$message}\n";
    }
}

$client = new Client();
$client->connect();