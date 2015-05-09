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

    /**
     * 通过ID找出相应表中的要求的字段
     */
    public static function findById($tabName, $priId, $fields='*'){
        $sql = 'SELECT %s FROM %s WHERE id=%d';
        return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));
    }

    /*
     * find()方法
     * 根据传入的参数，组装的sql 完成复杂的、功能强大的数据查询的方法   
     */
    public static function find($tables, $where=null, $fields='*', $group=null, $having=null, $order=null, $limit=null){
        $sql = 'SELECT '.self::parseFields($fields).' FROM '.$tables
                .self::parseWhere($where)
                .self::parseGroup($group)
                .self::parseHaving($having)
                .self::parseOrder($order)
                .self::parseLimit($limit);
        $result = self::getAll($sql);
        return count($result)==1 ? $result[0] : $result;
    }

    /*
     * add() 方法，向数据库表中新添加一条数据
     */
    public static function add($dataArr, $table){
        $keys = array_keys($dataArr);
        array_walk($keys,array('PdoMysql', 'addSpecialChar'));
        $fieldsStr = implode(',', $keys);
        $values = "'".implode("','", array_values($dataArr))."'";
        $sql = "INSERT INTO $table($fieldsStr) VALUES($values)";
        return self::execute($sql);
    }

    /*
     * update()
     * 更新记录方法
     */
    public static function update($dataArr, $table, $where){
        $set = '';
        foreach($dataArr as $key=>$val){
            $set .= "`".$key."`='".$val."',";
        }
        $sql = "UPDATE {$table} SET ".rtrim($set,",").self::parseWhere($where);
        return self::execute($sql);

    }

    /*
     * delete()
     * 删除记录方法 
     */
    public static function delete($table, $where=null, $delAll=false){
        if(empty($where) && empty($delAll)){
            return false;
        }
        $sql = "DELETE FROM {$table}".self::parseWhere($where);
        return self::execute($sql);
    }
    
    /*
     * getLastSql()
     * 获取最后一次执行的sql语句
     */
    public static function getLastSql(){
        $link = self::$link;
        if(!$link) return false;
        return self::$sqlStr;
    }    
    
    /*
     * 得到最后一次执行插入语句时得到的AUTO_INCREMENT
     */
    public static function getLastInsertId(){
        $link = self::$link;
        if(!$link) return false;
        return self::$lastInsertId;
    }

    /*
     * 获取数据库版本信息
     */
    public static function getDbVersion(){
        $link = self::$link;
        if(!$link) return false;
        return self::$dbVersion; 
    }

    /*
     * 得到数据库中的数据表
     */
    public static function showTables(){
        $tables = array();
        $link = self::$pdo_link;
        $res = self::getAll('SHOW TABLES');
        foreach($res as $key=>$val){
            $tables[$key] = current($val);
        }
        return $tables;
    }

    /**
     * 解析字段方法
     */
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
   
    /*
     * 解析 WHERE 条件
     */ 
    public static function parseWhere($where){
        $whereStr = '';
        if(!empty($where) && is_string($where)){
            $whereStr .= ' WHERE '.$where;
        }
        return $whereStr;
    }

    /*
     * 解析分组 GROUP BY
     */ 
    public static function parseGroup($group){
        $groupStr = '';
        if(!empty($group) && is_string($group)){
            $groupStr .= ' GROUP BY '.$group;
        }
        if(!empty($group) && is_array($group)){
            $groupStr .= ' GROUP BY '.implode(',', $group);
        }
        return $groupStr;
    }

    /*
     * 解析 HAVING
     */ 
    public static function parseHaving($having){
        $havingStr = '';
        if(!empty($having) && is_string($having)){
            $havingStr .= ' HAVING '.$having;
        }    
        return $havingStr;
    }    

    /*
     * 解析 ORDER BY
     */ 
    public static function parseOrder($order){
        $orderStr = '';
        if(!empty($order) && is_array($order)){
            $orderStr .= ' ORDER BY '.implode(',', $order);
        }
        if(!empty($order) && is_string($order)){
            $orderStr .= ' ORDER BY '.$order;
        }
        return $orderStr;
    }

    /*
     * 解析 LIMIT 
     */ 
    public static function parseLimit($limit){
        $limitStr = '';
        if(!empty($limit) && is_array($limit)){
            $limitStr = count($limit)>1 ? ' LIMIt '.$limit[0].', '.$limit[1] : ' LIMIT '.$limit[0];
        }
        if(!empty($limit) && (is_string($limit) || is_numeric($limit))){
            $limitStr = ' LIMIT '.$limit;
        }
        return $limitStr;
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

    /*
     * 关闭连接
     */
    public static function close(){
        self::$pdo_link = null;
    }
}



