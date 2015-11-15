<?php


    $str = 'serialize string';
    printf(serialize($str)."\n");

    $int = 3;
    printf(serialize($int)."\n");

    $boolTrue = true;
    $boolFalse = false;
    printf(serialize($boolTrue)."\n");
    printf(serialize($boolFalse)."\n");

    $val = null;
    printf(serialize($val)."\n");
    printf(serialize($var)."\n");

    $carObj = new car('BMW');
    printf(serialize($carObj)."\n");
    
    $obj = serialize($carObj);
    $unobj = unserialize($obj);
    printf($unobj->getName()."\n");




    class car{
        private $name;
        public $price=500000;
        protected $belong='wangxb';
        const LOCAL = 'xian';

        public function __construct($name){
            $this->name = $name;
        }

        public function getName(){
            return $this->name;
        }
    }

