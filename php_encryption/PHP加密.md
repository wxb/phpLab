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
		2.    
		Md5 加密本身是一个单项的过程，但是有些通常采用收集常见的Md5加密过的密文来对应破解Md5加密，此时我们需要在进行加密的过程中使用一些自己的特殊的处理，比如Md5加密两次，拼接一些特殊的字符后加密、加密后拼接一些特殊的字符等等方法  
