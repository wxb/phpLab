<?php
$array1 = array("a"=>1, "b"=>2, "c"=>3, "d"=>4, "e"=>5);
print_r(array_filter($array1, function($var)
{
    // returns whether the input integer is odd
    return trim($var);
}));
