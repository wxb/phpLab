<?php

class PdoMysql{
    public static $config = array();  // 设置链接参数
    public static $pdo_link = null;   // 保存链接标示符
    public static $pconn = false;   // 是否设置长连接
    public static $dbVersion = null; // 保存数据库版本
    public static $isConn = false;   // 是否连接成功
    public static $PDOStmt = null;   // 保存PDOStatment对象
    public static $sqlStr = null;    // 保存查询sql
    public static $errInfo = null;   // 保存sql执行的错误信息


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
                            'charset'  => DB_CHARSET,
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
        self::query($sql);
        $result = self::$PDOStmt->fetchAll(constant('PDO::FETCH_ASSOC'));
        return $result;
    }
    
    public static function free(){
        self::$PDOStmt = null;
    }

    public static function query($sql=''){
        $link = self::$pdo_link;
        if(!$link) return false;
        // 判断是否由之前的结果集，如果有的话先释放
        if(!empty(self::$PDOStmt)) self::free();
        self::$sqlStr = $sql;
        self::$PDOStmt = self::$pdo_link->prepare(self::$sqlStr);
        self::$PDOStmt->execute();
        // 这里我们可以使用try/catch 来抛出我们的错误，也可以使用pdo中的errorInof,errorCode方法输出错我信息
        // 我们使用我们自定义的方法来输出我们的错误信息
       self::throwPdoError();
    }
    
    /*
     * 自定义pdo处理错误信息输出方法
     */
    public static function throwPdoError(){
        $errObj = empty(self::$PDOStmt) ? self::$pdo_link : self::$PDOStmt;
        $arrError = $errObj->errorInfo();
        // 检查是否传进来了sql语句
        if('' == self::$sqlStr){
            self::throw_exception('sql是空，没有可执行的sql语句');
            return false;
        }
        // 00000 是MySQL返回正确执行的状态码
        if('00000' != $arrError[0]){
            self::$errInfo = 'SQL STATUS: '.$arrError[0].'<> ERROR CODE:'.$arrError[1].'<> ERROR INFOMETION:'.$arrError[2];
            self::throw_exception(self::$errInfo);
            return false;
        }
        
    }
    /*
     * 自定义错误处理函数
     */
    public static function throw_exception($msg){
        echo '<div style="color:red">'.$msg.'</div>';
    }
}

require('./config.pdomysql.php');
$pdomysql = new PdoMysql();
$sql = 'SELECT * FROM user';
print_r($pdomysql->getAll($sql));


