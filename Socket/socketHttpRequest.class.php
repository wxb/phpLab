<?php
/*
 * php+socket 编程，发送HTTP请求
 * 模拟下载、注册、登录、批量发帖
 */
// http 请求类接口
interface proto {
	/*
	 * 使用接口（interface），可以指定某个类必须实现哪些方法，但不需要定义这些方法的具体内容。
	 * 接口是通过 interface 关键字来定义的，就像定义一个标准的类一样，但其中定义所有的方法都是空的。
	 * 接口中定义的所有方法都必须是公有，这是接口的特性。
	 */
	// 连接url
	function conn($url);

	// 发送get请求
	function get();
	
	// 发送post请求
	function post();

	// 关闭连接
	function close();
}

class HttpRequest implements proto {
	// *** You must use double quotation marks ***
	// 这里是用双引号，单引号会报错
	const CRLF = "\r\n";

	protected $port = 80;    // default port

	protected $errno = -1;
	protected $errstr = '';
	protected $timeout = 3;

	protected $url = null;
	protected $version = 'HTTP/1.1';
	protected $fhandle = null;

	protected $line = array();
	protected $header = array();
	protected $body = array();

	protected $response = '';

	public function __construct($url) {
		if(!$this->handleURL($url)) {
			return false;
		}
		$this->conn($this->url);
		$this->setHeader('Host:'.$this->url['host']);
	}
	
	// 出来传入的url成数组
	protected function handleURL($url = null) {
		if(empty($url)) {
			return false;
		}
		$this->url = parse_url($url);
		if(!isset($this->url['port'])) {
			$this->url['port'] = $this->port;
		}
		return true;

	}

	// 构造请求行信息
	protected function setLine($method) {
		$this->line[0] = $method.' '.$this->url['path'].' '.$this->version;
	}

	// 构造请求头信息
	protected function setHeader($head) {
		$this->header[] = $head;
	}

	// 构造请求主体信息
	protected function setBody() {

	}

	// 连接url
	public function conn($url) {
		$this->fhandle = fsockopen($url['host'],$url['port'],$this->errno,$this->errstr,$this->timeout);
	}

	// get请求接口
	public function get($url = null) {
		if(!$this->handleURL($url) && empty($this->url)) {
			return false;
		}
		$this->setLine('GET');
		$this->request();
		return $this->response;
	}

	// post请求接口
	public function post() {

	}

	// 真正请求
	protected function request() {
		// 把请求行、请求头信息、主体信息 都放在一个数组中，便于拼接
		$req = array_merge($this->line,$this->header,array(''),$this->body,array(''));
		//print_r($req);
		$req = implode(self::CRLF, $req);
		//echo $req;
		$res = fwrite($this->fhandle, $req);
		//if($res) {
		/*
			while(!feof($this->fhandle)) {
				$this->response .= fread($this->fhandle, 1024);
				
			}
		 */
			$this->response = stream_get_contents($this->fhandle);
		//}
		//
		//$this->response = stream_get_contents($this->fhandle);
		$this->close(); //　关闭连接	
		//echo $this->response;
	}

	// 关闭连接
	public function close() {
		fclose($this->fhandle);
	}
}
/*
 * 当我利用上述代码给另一台服务器发送http请求时，发现，如果服务器处理请求时间过长，本地的PHP会中断请求，即所谓的超时中断，
 * 第一个怀疑的是PHP本身执行时间的超过限制，但想想也不应该，因为老早就按照这篇文章设置了“PHP执行时间限制”
 * （【推荐】PHP上传文件大小限制大全 ），仔细琢磨，想想，应该是http请求本身的一个时间限制，
 * 于是乎，就想到了怎么给http请求时间限制搞大一点。。。。。。查看PHP手册，果真有个参数 “ timeout ”，
 * 默认不知道多大，当把它的值设大一点，问题得已解决
 * 当然，如果我们不想修改配置文件，也可以使用这个函数，0 表示 没有时间方面的限制
 *
 * set_time_limit — 设置脚本最大执行时间
 */
set_time_limit(0);
$url = 'http://blog.csdn.net/heiyeshuwu/article/details/6920880';
//$url = 'http://localhost/phpinfo.php';
//$url = 'http://www.cnblogs.com/yimiao/archive/2011/10/28/2227603.html';
//$url = 'http://news.163.com/14/1126/04/ABUV0M4T00014AED.html';
//$url = 'http://www.w3school.com.cn/htmldom/dom_methods.asp';
//$url = 'http://news.163.com/14/1126/06/ABV5V5IE00014SEH.html';
//$url = 'http://www.cnblogs.com/qingling/archive/2013/09/02/3296959.html';
$http = new HttpRequest($url);
$result = $http->get();
echo $result;
//print_r($http);

?>
