<?php
//include_once dirname(__FILE__) . '/MemcacheSASL.php';
/**
 * aliyun memcache dervice
 *
 * @author Farmer.Li <lixu@huoyunren.com>
 */
class Cachealimemcached extends Cache
{
    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    function __construct($options=array()) {
        if ( !extension_loaded('memcached') ) {
            throwException('无法加载缓存类型:memcached');
        }

        $options = array_merge(array (
            'host'        =>  C('cache.alimemcachedhost') ? C('cache.alimemcachedhost') : '127.0.0.1',
            'port'        =>  C('cache.alimemcachedport') ? C('cache.alimemcachedport') : 11211,
            'username'    =>  C('cache.alimemcacheduser') ? C('cache.alimemcacheduser') : '',
            'password'    =>  C('cache.alimemcachedpassword') ? C('cache.alimemcachedpassword') : '',
            'timeout'     =>  C('cache.alimemcachedtimeout') ? C('cache.alimemcachedtimeout') : false
        ),$options);

        $this->options      =   $options;

        if ($this->options['username'] == '' || $this->options['password'] == '') {
            throwException('未设置aliyun memcache账号密码');
        }

        $this->options['expire'] =  isset($options['expire'])?  $options['expire']  :   C('cache.expire');
        $this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('cache.prefix');        
        $this->options['length'] =  isset($options['length'])?  $options['length']  :   0;
        $this->handler      =   new Memcached;
        $this->handler->setOption(Memcached::OPT_COMPRESSION, false); 
        $this->handler->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $this->handler->addServer($this->options['host'], $this->options['port']);
        $this->handler->setSaslAuthData($this->options['username'], $this->options['password']);
        //$this->handler = new MemcacheSASL;
        //$this->handler->addServer($this->options['host'], $this->options['port']);
        //$this->handler->setSaslAuthData($this->options['username'], $this->options['password']);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name) {
        N('cache_read',1);
        return $this->handler->get($this->options['prefix'].$name);
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
        if($this->handler->set($name, $value, $expire)) {
            if($this->options['length']>0) {
                // 记录缓存队列
                $this->queue($name);
            }
            return true;
        }
        return false;
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
    public function rm($name, $ttl = false) {
        $name   =   $this->options['prefix'].$name;
        return $ttl === false ?
            $this->handler->delete($name) :
            $this->handler->delete($name, $ttl);
    }

    /**
     * 清除缓存
     * @access public
     * @return boolen
     */
    public function clear() {
        return $this->handler->flush();
    }
}