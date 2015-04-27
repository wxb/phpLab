<?php

class PdoMysql{
    public static $config = array();  // 设置链接参数
    public static $pdo_link = null;   // 保存链接标示符
    public static $pconn = false;   // 是否设置长连接
    public static $dbVersion = null; // 保存数据库版本
    public static $isConn = false;   // 是否连接成功
    public static $PDOStmt = null;   // 保存PDOStatment对象

    public function __construct($dbConfig=''){
        if(!class_exists('PDO')){
            self::throw_exception('不支持PDO，请先开启PDO支持');
        }
        // 如果没有传入数据库配置，使用默认配置
        if(!is_array($dbConfig)){
            $dbConfig = array(
                            'hostname' => DB_HOST,
                            'username' => DB_USER,
                            'password' => DB_PWD,
                            'database' => DB_NAME,
                            'port'     => DB_PORT,
                            'dbtype'   => DB_TYPE,
                            'options'   => DB_OPTIONS,
                            'dsn'      => DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME
                        );
        }
        if(empty($dbConfig['hostname'])){
            self::$throw_exception('没有定义数据库配置');
        }
        self::$config = $dbConfig;
        if(empty(self::$config['options'])){
            self::$config['options'] = array();
        }
        if(!isset(self::$pdo_link)){
            $configs = self::$config;
            if(self::$pconn){
                $configs['options'][constant('PDO::ATTR_PERSISTENT')] = true;
            }
            try{
                self::$pdo_link = new PDO($configs['dsn'], $configs['username'], $configs['password'], $configs['options']);
            }catch(PDOException $e){
                self::throw_exceptiion($e->getMessage());
            }

            if(!self::$pdo_link){
                self::throw_exception('PDO连接错误');
                return false;
            }
            self::$pdo_link->exec('SET NAMES '.DB_CHARSET);
            self::$dbVersion = self::$pdo_link->getAttribute(constant('PDO::ATTR_SERVER_VERSION'));
            self::$isConn = true;
            unset($configs);
        
        }

    }

    /*
     * 获取所有字段方法
     **/
    public static function getAll($sql=''){
        if(!empty($sql)){
            self::query();
        }
        $result = self::$PDOStmt->fetchAll(constant('PDO::FETCH_ASSOC'));
        return $result;
    }
    
    /*
     * 自定义错误处理函数
     */
    public static function throw_exception($msg){
        echo '<div style="color:red">'.$msg.'</div>';
    }
}
