<?php


class Mysql{

    private static $config;
    private static $link;
    public function __construct(){
        self::$config = array(
                            'hostname' => DB_HOST,
                            'username' => DB_USER,
                            'password' => DB_PWD,
                            'database' => DB_NAME,
                            'port'     => DB_PORT,
                            'dbtype'   => DB_TYPE,
                            'charset'  => DB_CHARSET,
                            'dsn'      => DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME
                        );
        self::$link = new mysqli(self::$config['hostname'], self::$config['username'], self::$config['password'], self::$config['database']) or die('数据库连接失败');
        self::$link->query('set names '.self::$config['charset']) or die('字符集可是设置错误');
    }

    public function savePersonInfo($file, $fields, $line){
        $file = str_replace('\\', '/', $file);
        $sql = "LOAD DATA INFILE '".$file."' INTO TABLE info_person FIELDS TERMINATED BY '".$fields."' LINES TERMINATED BY '".$line."'";
        self::$link->query($sql) or die('Error:数据添加失败(insert error)');
    }

    public function countPersonInfo(){
        $sql = 'SELECT COUNT(iname) FROM info_person';
        $result = self::$link->query($sql) or die('Error: 统计数据信息失败(count error)');
        return $result->fetch_row()[0];
    }


    public function saveUnitInfo($file, $fields, $line){
        $file = str_replace('\\', '/', $file);
        $sql = "LOAD DATA INFILE '".$file."' INTO TABLE info_unit FIELDS TERMINATED BY '".$fields."' LINES TERMINATED BY '".$line."'";
        self::$link->query($sql) or die('Error:数据添加失败(insert error)');
    }

    public function countUnitInfo(){
        $sql = 'SELECT COUNT(iname) FROM info_unit';
        $result = self::$link->query($sql) or die('Error: 统计数据信息失败(count error)');
        return $result->fetch_row()[0];
    }
}
