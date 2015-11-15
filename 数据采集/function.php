<?php

function getPageInfo($url, $client, $page=1){
    $content = $client->postResponseInfo($url, array('currentPage'=>$page));
    $page_pattern = '/页 ([1-9][0-9]*)\/([1-9][0-9]*) 共([1-9][0-9]*)条/';
    preg_match($page_pattern, $content, $page_matches);
    $page_matches[0] = $content;
    return $page_matches;

}

function getId($content){
    $id_pattern = '/<a href="javascript:void\(0\);" class="iView" id="([\d]*)">查看<\/a>/';
    preg_match_all($id_pattern, $content, $id_matches); 
    $id_arr = $id_matches[1];
    return $id_arr;
}



