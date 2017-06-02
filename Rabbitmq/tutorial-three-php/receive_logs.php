<?php
/**
 * Created by PhpStorm.
 * Project    : phpLab
 * File       : receive_logs.php
 * Author     : wangxb
 * Email      : wangxiaobo@parkingwang.com
 * Date       : 2017-05-30 18:56
 * Description:
 */

require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

//$channel->exchange_declare('logs', 'fanout', false, false, false);
$channel->exchange_declare('arm_msg_receiver', 'direct', false, false, false);

//list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);
$channel->queue_declare("arm_msg_receiver", false, false, true, false);

$channel->queue_bind('arm_msg_receiver', 'arm_msg_receiver', 'arm_msg_receiver');

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function($msg){
    echo ' [x] ', $msg->body, "\n";
};

$channel->basic_consume('arm_msg_receiver', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
