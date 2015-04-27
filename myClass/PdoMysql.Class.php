<?php

class PdoMysql{
    public function __construct(){
        if(!class_exists('PDO')){
            echo 'PDO未开启';
        }
    }
}
