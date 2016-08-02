<?php
/**
 * Redis缓存类
 */
class CacheRedis extends Cache {
	 /**
	 * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options=array()) {
        if ( !extension_loaded('redis') ) {
            throwException('不支持'.':redis');
        }
        if(empty($options)) {
            $options = array (
                'host'          => C('cache.redishost') ? C('cache.redishost') : '127.0.0.1',
                'port'          => C('cache.redisport') ? C('cache.redisport') : 6379,
                'passwd'          => C('cache.redispasswd') ? C('cache.redispasswd') : '',
                'timeout'       => C('cache.redistimeout') ? C('cache.redistimeout') : false,
                'persistent'    => false,
            );
        }
        $this->options =  $options;
        $this->options['expire'] =  isset($options['expire'])?  $options['expire']  :   C('cache.expire');
        $this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('cache.prefix');        
        $this->options['length'] =  isset($options['length'])?  $options['length']  :   0;        
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        $this->handler  = new Redis;
        $options['timeout'] === false ?
            $this->handler->$func($options['host'], $options['port']) :
            $this->handler->$func($options['host'], $options['port'], $options['timeout']);
        if ($options['passwd']) {
            $this->handler->auth($options['passwd']);
        }
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        N('cache_read',1);
        $value = $this->handler->get($this->options['prefix'].$name);
        //$jsonData  = json_decode( $value, true );
        //return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
        return $value;
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolen
     */
    public function set($name, $value, $expire = null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        $name   =   $this->options['prefix'].$name;
        //对数组/对象数据进行缓存处理，保证数据完整性
        $value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
        if(is_int($expire)) {
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        if($result && $this->options['length']>0) {
            // 记录缓存队列
            $this->queue($name);
        }
        return $result;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
    public function rm($name) {
        return $this->handler->delete($this->options['prefix'].$name);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolen
     */
    public function clear() {
        return $this->handler->flushDB();
    }

}
