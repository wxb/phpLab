## PHP加密    
* PHP加密的几种形式：   
	* Md5（）加密算法   
	* Crypt（）加密算法   
	* Sha1（）加密算法   
	* URL编码加密技术   
	* Base64编码加密技术   
 
	
* 详细说明
	* Md5() - 测试demo：md5.php
		1. md5 — 计算字符串的 MD5 散列值  
			> string md5 ( string $str [, bool $raw_output = false ] )  
			> str 原始字符串。   
			> raw_output 如果可选的 raw_output 被设置为 TRUE，那么 MD5 报文摘要将以16字节长度的原始二进制格式返回。
		2. Md5 加密本身是一个单项的过程，但是有些通常采用收集常见的Md5加密过的密文来对应破解Md5加密，此时我们需要在进行加密的过程中使用一些自己的特殊的处理，比如Md5加密两次，拼接一些特殊的字符后加密、加密后拼接一些特殊的字符等等方法  
	* crypt ( string $str [, string $salt ] ) - 测试Demo：crypt.php   
		1. crypt() 返回一个基于标准 UNIX DES 算法或系统上其他可用的替代算法的散列字符串。    
		2. $str: 要加密的字符串   
		   $salt: 盐值（加密时的干扰码，使加密更加安全）   
		3. [参考手册-crypt](http://php.net/manual/zh/function.crypt.php)   
		4. CRYPT_MD5 - MD5 散列使用一个以 $1$ 开始的 12 字符的字符串盐值。(盐值就是以$1$开始 中间最多8个字符 然后以 $结束，例如：$1$6n2.V33.$yMk66jq2vq5LxszxA7.4w/ 前面的是盐值，后面的是密文，当我们使用自动盐值时，这个密文每次都会变化，因为每次都会生成不同的盐值)
		5. CRYPT_STD_DES - 基于标准 DES 算法的散列使用 "./0-9A-Za-z" 字符中的两个字符作为盐值（当盐值$salt设置超过2个字符，函数也只截取两个字符）    
	* sha1 ( string $str [, bool $raw_output = false ] )   
		1. 返回一个40个字符长度的字符串，MD5返回32个字符的散列值   
		2.  raw_output 参数被设置为 TRUE，那么 sha1 摘要将以 20 字符长度的原始格式返回，否则返回值是一个 40 字符长度的十六进制数字。  
	* URL编码加密技术    
		1. PHP使用urlencode()函数对URL进行编码，使用urldecode()进行解码    
		2. urlencode ( string $str )  
			返回字符串中除了 -_. 之外的所有非字母数字字符都将被替换成百分号（%）后跟两位十六进制数，空格则编码为加号（+）。此编码与 WWW 表单 POST 数据的编码方式是一样的
		3. urldecode ( string $str ) 解码由urlencode编码的字符串， 加号（'+'）被解码成一个空格字符。   
		4. rawurlencode 和 rawurldecode 相对应于上面的两个函数，区别是这两个函数在编码空格的时候会编码成 %20，而rulencode会将空格编码成 + 号 ，urldecode将+解码成空格    
		5. demo：urlcode.php
