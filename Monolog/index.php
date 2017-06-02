<?php
/**
 * Created by PhpStorm.
 * Project    : phpLab
 * File       : index.php
 * Author     : wangxb
 * Date       : 2017-05-28 20:12
 * Description:
 */

require_once __DIR__ . '/vendor/autoload.php';


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Processor\PsrLogMessageProcessor;

// Create the logger
$logger = new Logger('logger');
$db_logger = new Logger('db_logger');
// Now add some handlers
$logger->pushHandler(new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG));
$db_logger->pushHandler(new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());

// You can now use your logger
//$logger->info('Adding a new user', array('username' => 'Seldaek'));
//$db_logger->info('My logger is now ready');


$db_logger->pushProcessor(new PsrLogMessageProcessor());

/*
$db_logger->pushProcessor(function ($record) {
    $record['extra'] = ['first Hello world!', 12];

    return $record;
});
*/

$db_logger->info('Hello, {name}! the Time is: {time}', array('time' => date('Y-m-d H:i:s'), 'name'=>'王晓勃'));
