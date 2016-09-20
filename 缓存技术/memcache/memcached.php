<?PHP
namespace Index\Common;

class Memcachedzx {
    private $connection = null;

    public function __construct(){
        $this->connection = new \Memcached();
        $this->connection->addServers(array(
            array(C('MEMCACHED_HOST'), C('MEMCACHED_PORT'), 10)
        ));
        $this->connection->setOption(\Memcached::OPT_COMPRESSION, false);
        $this->connection->setOption(\Memcached::OPT_CONNECT_TIMEOUT, 50);
        $this->connection->setOption(\Memcached::OPT_HASH, \Memcached::HASH_CRC);
        if(ini_get('memcached.use_sasl')) {
            $this->connection->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
            $this->connection->setSaslAuthData(C('MEMCACHED_SASL_USERNAME'), C('MEMCACHED_SASL_PASSWORD'));
        }
    }

    public function randomStr($length = 6, $numeric = 0) {
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz-_@';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    public function write($key, $value, $duration = 3600){
        if (!$this->connection) return false;
        $key  = $this->encodeKey($key);
        return $this->connection->set($key, $value, time() + $duration);
    }

    public function read($key){
        if (!$this->connection) return NULL;
        $key    = $this->encodeKey($key);
        $ret     = $this->connection->get($key);
        return $ret;
    }

    public function delete($key){
        if (!$this->connection) return false;
        $key    = $this->encodeKey($key);
        return $this->connection->delete($key);
    }

    public function __destruct(){
        if($this->connection){
            unset($this->connection);
        }
    }

    public function encodeKey($key) {
        return urlencode($key);
    }
}
