<?php
	header("content-type:text/html; charset=utf-8");

	$str = 'wangxb123';
	// 如果没有提供盐值，系统会在每次执行crypt函数时自动生成一个盐值，这样加密后的密文每次都是不一样的，因为盐值不一样
	echo crypt($str);


	echo '<hr/>';
	// 判断 标准DES算法可用
	if (CRYPT_STD_DES == 1) {
		// 尽管盐值有多个字符串，但是还是只会取用前两个字符：he
		// 所以结果就是：Standard DES: heSr3qXekjhN6 ，并且由于盐值给定，这样无论你何时执行这个函数，输出不会变化
    	echo 'Standard DES: ' . crypt('rasmuslerdorf', 'hello everyone everybody') . "\n";
	}

	echo "<hr/>";
	// 判断 MD5算法可用
	if (CRYPT_MD5 == 1) {
		// CRYPT_MD5 算法 是以 $1$ 开始 以$结束,中间添加不超过8个字符，总的字符不超过12个
	    echo 'MD5:          ' . crypt('rasmuslerdorf', '$1$rasmusle$') . "\n";
	}

	
	
