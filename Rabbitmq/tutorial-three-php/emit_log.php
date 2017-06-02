<?php
/**
 * Created by PhpStorm.
 * Project    : phpLab
 * File       : emit_log.php
 * Author     : wangxb
 * Date       : 2017-05-31 14:28
 * Description:
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->exchange_declare('arm_msg_receiver', 'direct', false, false, false);

$data = implode(' ', array_slice($argv, 1));

if(empty($data)) $data = "info: Hello World!";
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'arm_msg_receiver', 'arm_msg_receiver');

echo " [x] Sent ", $data, "\n";

$channel->close();
$connection->close();

