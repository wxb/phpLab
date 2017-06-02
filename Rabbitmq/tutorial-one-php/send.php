<?php
/**
 * Created by PhpStorm.
 * Project    : phpLab
 * File       : send.php
 * Author     : wangxb
 * Date       : 2017-05-30 13:40
 * Description:
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$message = $argv;
unset($message[0]);

$msg = new AMQPMessage(json_encode($message));
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();