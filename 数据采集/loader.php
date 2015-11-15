<?php

namespace Load;

class Loader{

    private static $file_dir = __DIR__;

    static function autoload($class){
        require self::$file_dir.'/'.str_replace('\\', '/', $class).'.php';
    } 
}
