<?php

/**
 * language: php
 * version: 5.6.24
 * 
 * run cmd: php index.php
 * 
 * Problem Two: Conference Track Management
 * 
 */

// 设置时区
date_default_timezone_set("Asia/Shanghai");


/**
 * 解析talks输入文件
 *
 * @param string $file 
 * @return void
 */
function parseTalksFile($file)
{
    $input = file($file);

    $talks = [];
    foreach ($input as $line) {
        $arr = explode(' ', str_replace(PHP_EOL, '', $line));
        $k = str_replace(PHP_EOL, '', $line);
        $v = $arr[count($arr) - 1];
        if ($v == 'lightning') {
            $v = '5min';
        }
        $talks[$k] = str_replace('min', '', $v);
    }

    return $talks;
}

/**
 * talks数组转换成格式化track文档字符串
 *
 * @param array $talksMap
 * @return void
 */
function convertTalksToTrackStr($talksMap)
{
    // 计算总的talk时长 和 track数
    $totalMinutes = array_sum($talksMap);
    $trackNum = ceil($totalMinutes / (7 * 60));

    // 按照trackNum数 写入到不同的track下 
    $trackStr = '';
    $loop = count($talksMap);
    arsort($talksMap);
    reset($talksMap);
    for ($i = 0; $i < $trackNum; $i++) {
        $morning = strtotime('09:00 AM');
        $afternon = strtotime('01:00 PM');
        $trackStr .= "Track " . ($i + 1) . ":\n";
    
        // 上午安排会议
        while ($morning + current($talksMap) * 60 <= strtotime('12:00 PM') && $loop > 0) {
            list($key, $val) = each($talksMap);
            $trackStr .= date('h:iA ', $morning) . $key . "\n";

            $morning += $val * 60;
            $loop -= 1;
        }

        $trackStr .= "12:00PM Lunch\n";

        // 下午安排会议
        while ($afternon + current($talksMap) * 60 <= strtotime('05:00 PM') && $loop > 0) {
            list($key, $val) = each($talksMap);
            $trackStr .= date('h:iA ', $afternon) . $key . "\n";

            $afternon += $val * 60;
            $loop -= 1;
        }

        $trackStr .= "05:00PM Networking Event\n";
    }

    return $trackStr;
}


// 指定输入输出文件
$inputFile  = './input.text';
$outputFile = './output.text';

// 解析文件到数组中
$talksArr = parseTalksFile($inputFile);
// 转换成输出字符串
$trackStr = convertTalksToTrackStr($talksArr);
// 输出到文件
file_put_contents($outputFile, $trackStr);
