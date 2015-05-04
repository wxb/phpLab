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
    public static $lastInsertId = null; //保存插入操作时返回的插入数据的ID或序列值
    public static $affectRows = 0;   // 保存增删改操作时受影响的行数

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
    
    /*
     * 释放PDOStatment对象
     */
    public static function free(){
        self::$PDOStmt = null;
    }

    /*
     * 执行sql查询 
     */
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
    
    /**
     * 返回结果集中的一条数据
     */
    public static function getRow($sql=''){
        self::query($sql);
        $result = self::$PDOStmt->fetch(constant('PDO::FETCH_ASSOC'));
        return $result;
    }
    
    /**
     * 执行增、删、改操作方法
     */
    public static function execute($sql=''){
        $link = self::$pdo_link;
        if(!$link) return false;
        if(!empty(self::$PDOStmt)) self::free();
        self::$sqlStr = $sql;
        $result = $link->exec(self::$sqlStr);
        // 检测sql执行过程中是否出现错误
        self::throwPdoError();
        if($result){
            // 如果是插入操作 ，调用PDO::lastInsertId() 方法返回插入行ID，然后保存
            self::$lastInsertId = $link->lastInsertId();
            // 保存sql执行后返回的受影响的行数值
            self::$affectRows = $result;
            return self::$affectRows;
        }else{
            return false;
        }
        
    }

    public static function findById($tabName, $priId, $fields='*'){
        $sql = 'SELECT %s FROM %s WHERE id=%d';
        return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));
    }

    public static function parseFields($fields){
        // 传递进来的要查询的字段，我们规定形式只能是数组和字符串
        if(is_array($fields)){
            // 使用用户自定义函数对数组中的每个元素做回调处理
            array_walk($fields, array('PdoMysql', 'addSpecialChar'));
            $fieldsStr = implode(',', $fields);
        }elseif(is_string($fields)){
            // 反引号常见在SQL语句中来包含关键字
            // === false 是防止 反引号 出现在 0 的位置 strpos将会返回0 == false，所以这里使用 恒等
            if(strpos('`', $fields) === false){
                $fields = explode(',', $fields);
                // 使用用户自定义函数对数组中的每个元素做回调处理
                array_walk($fields, array('PdoMysql', 'addSpecialChar'));
                $fieldsStr = implode(',', $fields);
            }
        }else{
            $fieldsStr = '*';
        }
        return $fieldsStr;
    }
    
    /**
     * 为查询字段添加反引号 方法
     * 为了防止查询的字段可能和sql中的保留字冲突，通常我们给查询的地段添加 反引号
     * 注意方法中是：引用传值的 &$value
     */
    public static function addSpecialChar(&$value){
        if('*'===$value || strpos($value, '.')!==false || strpos($value,'`')!==false){

        }elseif(strpos($value,'`')===false){
            $value = '`'.trim($value).'`';
        }
        return $value;
        
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



