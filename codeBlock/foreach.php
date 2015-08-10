<?php

$a = array(1, 2, 3);
foreach($a as $v){
    var_dump(current($a));
}

echo '<hr/>';
$a = array(1, 2, 3);
$b = &$a;
foreach($a as $v){
    var_dump(current($a));
}

echo '<hr/>';
$a = array(1, 2, 3);
$b = $a;
foreach($a as $v){
    var_dump(current($a));
}


